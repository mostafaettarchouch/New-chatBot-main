<?php

namespace App\Services;

use App\Models\Language;
use App\Models\LegalProcedure;
use App\Models\Keyword;
use App\Models\Question;
use App\Models\UnansweredQuestion;

class ChatMatchingService
{
    public function processMessage(string $message): array
    {
        $language = $this->detectLanguage($message);
        $normalized = $this->normalizeText($message, $language->code);
        $tokens = $this->extractTokens($normalized, $language->code);

        if (empty($tokens)) {
            return $this->handleNoMatch($message, $language);
        }

        $matches = $this->matchKeywords($normalized, $tokens, $language->id);
        $bestMatch = $this->getBestMatch($matches);

        if ($bestMatch && $bestMatch['confidence'] >= 0.8) {
            Question::create([
                'question_text' => $message,
                'language_id' => $language->id,
                'legal_procedure_id' => $bestMatch['procedure']->id,
                'asked_at' => now(),
            ]);

            return [
                'response' => $bestMatch['procedure']->summary ?: $bestMatch['procedure']->description,
                'procedure' => $bestMatch['procedure'],
                'steps' => $bestMatch['procedure']->procedureSteps,
                'matched' => true,
            ];
        }

        return $this->handleNoMatch($message, $language);
    }

    private function handleNoMatch(string $message, Language $language): array
    {
        UnansweredQuestion::create([
            'question_text' => $message,
            'language_id' => $language->id,
            'asked_at' => now(),
        ]);

        return [
            'response' => $this->getFallbackMessage($language->code),
            'matched' => false,
        ];
    }

    private function detectLanguage(string $message): Language
    {
        $arabicChars = preg_match('/[\x{0600}-\x{06FF}]/u', $message);

        if ($arabicChars) {
            return Language::where('code', 'ar')->first();
        }

        return Language::where('is_default', true)->first() ?? Language::where('code', 'ar')->first();
    }

    private function normalizeText(string $message, string $langCode): string
    {
        $message = trim($message);

        if ($langCode === 'ar') {
            $message = preg_replace('/[\p{P}\p{S}]/u', ' ', $message);
            $message = preg_replace('/[\x{064B}-\x{0652}]/u', '', $message);
            $message = str_replace(['أ', 'إ', 'آ', 'ى', 'ؤ', 'ئ', 'ة', 'ـ'], ['ا', 'ا', 'ا', 'ي', 'و', 'ي', 'ه', ''], $message);
            $message = preg_replace('/[^\p{Arabic}\s]/u', ' ', $message);
            $message = preg_replace('/\s+/u', ' ', $message);
            return mb_strtolower(trim($message), 'UTF-8');
        }

        $message = mb_strtolower($message, 'UTF-8');
        $message = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $message);
        $message = preg_replace('/\s+/u', ' ', $message);

        return trim($message);
    }

    private function extractTokens(string $message, string $langCode): array
    {
        $words = array_filter(explode(' ', $message), fn ($word) => $word !== '');
        $stopWords = $this->getStopWords($langCode);
        return array_values(array_filter($words, fn ($word) => !in_array($word, $stopWords, true)));
    }

    private function getStopWords(string $langCode): array
    {
        return match ($langCode) {
            'ar' => ['و', 'في', 'من', 'على', 'الى', 'إلى', 'مع', 'عن', 'هذا', 'هذه', 'هو', 'هي', 'لا', 'لم', 'لن', 'ما', 'كيف', 'متى', 'أين', 'هل', 'لماذا'],
            default => [],
        };
    }

    private function matchKeywords(string $normalized, array $tokens, int $languageId): array
    {
        $procedures = LegalProcedure::with(['procedureSteps', 'keywords'])
            ->where('language_id', $languageId)
            ->get();

        $matches = [];

        foreach ($procedures as $procedure) {
            $score = 0;
            $matchedKeywords = [];

            foreach ($procedure->keywords as $keyword) {
                $keywordText = $this->normalizeText($keyword->keyword, 'ar');

                foreach ($tokens as $token) {
                    if ($token === $keywordText || str_contains($keywordText, $token) || str_contains($normalized, $keywordText)) {
                        $score += $keyword->weight;
                        $matchedKeywords[] = $keyword->keyword;
                        break;
                    }
                }
            }

            if ($score > 0) {
                $confidence = min($score / max(count($tokens), 1), 1.0);
                $matches[$procedure->id] = [
                    'procedure' => $procedure,
                    'score' => $score,
                    'confidence' => $confidence,
                    'matched' => array_unique($matchedKeywords),
                ];
            }
        }

        return $matches;
    }

    private function getBestMatch(array $matches): ?array
    {
        if (empty($matches)) {
            return null;
        }

        usort($matches, fn ($a, $b) => $b['confidence'] <=> $a['confidence']);
        return $matches[0];
    }

    private function getFallbackMessage(string $langCode): string
    {
        return match ($langCode) {
            'ar' => 'عذراً، لم أتمكن من العثور على إجابة دقيقة لسؤالك. سيتم مراجعته من قبل الإدارة.',
            default => 'عذراً، لم أتمكن من العثور على إجابة دقيقة لسؤالك. سيتم مراجعته من قبل الإدارة.',
        };
    }
}
