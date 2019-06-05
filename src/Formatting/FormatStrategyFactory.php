<?php

namespace App\Formatting;

use App\Repository\FormatRepository;
use Doctrine\ORM\EntityManager;
use function mb_substr;

/**
 * Description of FormatStrategyFactory
 *
 * @author Matthias
 */
class FormatStrategyFactory {

    /**
     *
     * @var EntityManager
     */
    private $manager;

    /**
     *
     * @var FormatRepository
     */
    private $formatRepo;
    private $tags = [];

    public function __construct(EntityManager $manager) {
        $this->manager = $manager;
    }

    public function getStrategyForText(string $text, bool $isPost) {
        if (strlen($text) == 0 || mb_substr($text, 0, 1) != '`') {
            return new DoNothingFormatStrategy();
        }

        $tag = mb_substr($text, 1, 1);
        switch ($tag) {
            case '#':
                return new HexCodeFormatStrategy();
            case 'n':
                return new BreakFormatStrategy();
            case 'b':
                return new BoldFormatStrategy();
            case 'i':
                return new ItalicFormatStrategy();
            case '°':
                return new GradientFormatStrategy();
//            case '^':
//            case '°':
            default:
                array_push($this->tags, $tag);
                return new StandardFormatStrategy($this->getFormatRepo(), $isPost);
        }
    }

    public function loadTags() {
        if ($this->formatRepo) {
            $this->formatRepo->loadFormats($this->tags);
        }
    }

    private function getFormatRepo(): FormatRepository {
        if (!$this->formatRepo) {
            $this->formatRepo = $this->manager->getRepository('App:Format');
        }
        return $this->formatRepo;
    }

}
