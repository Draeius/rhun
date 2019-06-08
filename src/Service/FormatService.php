<?php

namespace App\Service;

use App\Formatting\FormatStrategy;
use App\Formatting\FormatStrategyFactory;
use App\Repository\FormatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Stopwatch\Stopwatch;


/**
 * Description of FormatService
 *
 * @author Draeius
 */
class FormatService {

    /**
     *
     * @var FormatStrategy[]
     */
    private $pendingStrategies;

    /**
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     *
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     *
     * @var FormatRepository
     */
    private $formatRepo;
    
    public function __construct(EntityManagerInterface $eManager, FormatRepository $formatRepo, Stopwatch $stopwatch) {
        $this->pendingStrategies = array();
        $this->entityManager = $eManager;
        $this->stopwatch = $stopwatch;
        $this->formatRepo = $formatRepo;
    }

    public function parse(?string $text, bool $allowedOnly = false): string {
        if ($text == '') {
            return '';
        }

        $this->stopwatch->start('formatting');

        $array = preg_split('/(?=`[^`])/', $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $result = $this->formatArray($array, $allowedOnly); //verarbeiten

        $this->stopwatch->stop('formatting');
        return $result;
    }

    private function formatArray(array $array, bool $allowedOnly): string {
        $count = count($array);

        $strategies = $this->getStrategies($array, $count, $allowedOnly);

        for ($index = 0; $index < $count; $index++) {
            $text = $array[$index] != null ? $array[$index] : '';

            $strategy = $strategies[$index];

            $lastStrategy = array_pop($this->pendingStrategies);
            if ($lastStrategy == null || $lastStrategy->openAfterStrategy($strategy)) {
                $array[$index] = $strategy->start($text);
            }

            if ($lastStrategy != null && $lastStrategy->closeForStrategy($strategy)) {
                $array[$index] = $lastStrategy->end($array[$index]);
            } else {
                array_push($this->pendingStrategies, $lastStrategy);
            }

            if ($strategy->needsClosing()) {
                array_push($this->pendingStrategies, $strategy);
            }
        }

        $this->closeAll($array);

        return implode('', $array);
    }

    private function closeAll(array &$array) {
        $text = '';
        while ($strategy = array_pop($this->pendingStrategies)) {
            $text = $strategy->end($text);
        }
        array_push($array, $text);
    }

    private function getStrategies(array &$array, int $arraySize, bool $allowedOnly) {
        $factory = new FormatStrategyFactory($this->entityManager, $this->formatRepo);
        $strategies = [];

        for ($index = 0; $index < $arraySize; $index++) {
            $text = $array[$index] != null ? $array[$index] : '';
            $strategies[$index] = $factory->getStrategyForText($text, $allowedOnly);
        }
        $factory->loadTags();
        return $strategies;
    }

}
