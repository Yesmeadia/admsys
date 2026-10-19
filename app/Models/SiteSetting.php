<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key (cached for 1 hour)
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("site_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key
     */
    public static function set($key, $value)
    {
        Cache::forget("site_setting_{$key}");
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Check if a form is enabled
     */
    public static function isFormEnabled($formKey)
    {
        return self::get($formKey . '_enabled', '1') === '1';
    }

    /**
     * Get classes array for a specific form (form1, form2, form3)
     */
    public static function getFormClasses(string $formKey): array
    {
        $defaults = [
            'form1' => ['Nursery', 'LKG', 'UKG', '1ST'],
            'form2' => ['2ND', '3RD', '4TH', '5TH', '6TH', '7TH'],
            'form3' => ['Class 11'],
        ];

        $val = self::get("{$formKey}_classes");
        if ($val !== null && trim($val) !== '') {
            $items = preg_split('/[\r\n,]+/', $val);
            $classes = array_values(array_filter(array_map('trim', $items)));
            if (!empty($classes)) {
                return $classes;
            }
        }

        return $defaults[$formKey] ?? [];
    }

    /**
     * Get raw classes string for form settings input
     */
    public static function getFormClassesRaw(string $formKey): string
    {
        $val = self::get("{$formKey}_classes");
        if ($val !== null && trim($val) !== '') {
            return trim($val);
        }

        $classes = self::getFormClasses($formKey);
        return implode(', ', $classes);
    }

    /**
     * Default Form 3 Subject Groups structure
     */
    public static function defaultForm3SubjectGroups(): array
    {
        return [
            'Science' => [
                'Group E (Medical 1)' => ['English', 'Physics', 'Chemistry', 'Biology', 'EVS'],
                'Group F (Medical 2)' => ['English', 'Physics', 'Chemistry', 'Biology', 'Islamic Studies'],
                'Group G (Non Medical 1)' => ['English', 'Physics', 'Chemistry', 'Mathematics', 'EVS'],
                'Group H (Non Medical 2)' => ['English', 'Physics', 'Chemistry', 'Mathematics', 'Islamic Studies'],
            ],
            'Arts' => [
                'Group A (Arts 1)' => ['English', 'History', 'Politics', 'Education', 'EVS'],
                'Group B (Arts 2)' => ['English', 'History', 'Politics', 'Education', 'Islamic Studies'],
                'Group C (Inter-Arts 1)' => ['English', 'History', 'Politics', 'Mathematics', 'EVS'],
                'Group D (Inter-Arts 2)' => ['English', 'History', 'Politics', 'Mathematics', 'Islamic Studies'],
            ],
        ];
    }

    /**
     * Get Form 3 subject groups as structured array
     */
    public static function getForm3SubjectGroups(): array
    {
        $json = self::get('form3_subject_groups');
        if (!empty($json)) {
            $decoded = json_decode($json, true);
            if (is_array($decoded) && !empty($decoded)) {
                return $decoded;
            }
        }

        return self::defaultForm3SubjectGroups();
    }

    /**
     * Get raw text for a specific stream's subject groups
     */
    public static function getForm3StreamGroupsRaw(string $stream): string
    {
        $raw = self::get("form3_" . strtolower($stream) . "_groups_raw");
        if (!empty($raw)) {
            return trim($raw);
        }

        $groups = self::getForm3SubjectGroups();
        $streamGroups = $groups[$stream] ?? [];
        $lines = [];
        foreach ($streamGroups as $groupName => $subjects) {
            $subStr = is_array($subjects) ? implode(', ', $subjects) : $subjects;
            $lines[] = "{$groupName} | {$subStr}";
        }

        return implode("\n", $lines);
    }

    /**
     * Save Form 3 subject groups from admin settings input
     */
    public static function saveForm3SubjectGroups(?string $scienceRaw, ?string $artsRaw): void
    {
        $parseStream = function (?string $rawText) {
            $result = [];
            if (empty($rawText)) {
                return $result;
            }
            $lines = explode("\n", str_replace("\r", "", $rawText));
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || str_starts_with($line, '#')) {
                    continue;
                }
                // Split by | or :
                $parts = preg_split('/[|:]/', $line, 2);
                if (count($parts) === 2) {
                    $groupName = trim($parts[0]);
                    $subjectList = array_values(array_filter(array_map('trim', explode(',', $parts[1]))));
                    if (!empty($groupName) && !empty($subjectList)) {
                        $result[$groupName] = $subjectList;
                    }
                } elseif (count($parts) === 1) {
                    $groupName = trim($parts[0]);
                    if (!empty($groupName)) {
                        $result[$groupName] = [];
                    }
                }
            }
            return $result;
        };

        $scienceParsed = $parseStream($scienceRaw);
        $artsParsed = $parseStream($artsRaw);

        // Fallback to default if empty
        if (empty($scienceParsed) && empty($artsParsed)) {
            $compiled = self::defaultForm3SubjectGroups();
        } else {
            $compiled = [];
            if (!empty($scienceParsed)) {
                $compiled['Science'] = $scienceParsed;
            }
            if (!empty($artsParsed)) {
                $compiled['Arts'] = $artsParsed;
            }
        }

        self::set('form3_science_groups_raw', $scienceRaw ? trim($scienceRaw) : '');
        self::set('form3_arts_groups_raw', $artsRaw ? trim($artsRaw) : '');
        self::set('form3_subject_groups', json_encode($compiled, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Find the stream (Science or Arts) that owns a given subject group name
     */
    public static function getStreamForSubjectGroup(string $groupName): string
    {
        $groups = self::getForm3SubjectGroups();
        foreach ($groups as $stream => $streamGroups) {
            if (isset($streamGroups[$groupName])) {
                return $stream;
            }
        }

        $lower = strtolower($groupName);
        if (str_contains($lower, 'arts')) {
            return 'Arts';
        }
        if (str_contains($lower, 'science') || str_contains($lower, 'medical')) {
            return 'Science';
        }

        return 'General';
    }

    /**
     * Get subjects list for a given group
     */
    public static function getSubjectsForGroup(string $groupName): array
    {
        $groups = self::getForm3SubjectGroups();
        foreach ($groups as $stream => $streamGroups) {
            if (isset($streamGroups[$groupName])) {
                return (array) $streamGroups[$groupName];
            }
        }

        return [];
    }
}
