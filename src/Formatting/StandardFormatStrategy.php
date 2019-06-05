<?php

namespace App\Formatting;

use App\Repository\FormatRepository;

/**
 * Description of StandardFormatStrategy
 *
 * @author Matthias
 */
class StandardFormatStrategy implements FormatStrategy {

    /**
     *
     * @var FormatRepository
     */
    private $repo;

    /**
     * 
     * @var bool $isPost
     */
    private $isPost;

    public function __construct(FormatRepository $repo, bool $isPost) {
        $this->repo = $repo;
        $this->isPost = $isPost;
    }

    public function start(string $string): string {

        if (!empty($string)) {
            if ($string[0] != '`') {
                return $string;
            }
        }
        $tag = $this->getTag($string);
        $format = $this->repo->findByCode($tag);
        if (!$format) {
            return $string;
        }

        if ($this->isPost && !$format->getIsAllowed()) {
            return $string;
        }

        $cleanString = mb_substr($string, 2);

        return '<span style="color: #' . $format->getColor() . ';">' . $cleanString;
    }

    public function end(string $string): string {
        return '</span>' . $string;
    }

    public function needsClosing(): bool {
        return true;
    }

    public function openAfterStrategy(FormatStrategy $strategy): bool {
        return true;
    }

    public function closeForStrategy(FormatStrategy $nextStrategy): bool {
        if ($nextStrategy instanceof HexCodeFormatStrategy) {
            return true;
        }
        if ($nextStrategy instanceof StandardFormatStrategy) {
            return true;
        }
        return false;
    }

    private function getTag(string $string): string {
        return mb_substr($string, 1, 1);
    }

}
