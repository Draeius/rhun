<?php

namespace App\Formatting;

/**
 * Description of FormatStrategy
 *
 * @author Draeius
 */
interface FormatStrategy {

    /**
     * 
     * @param String $string
     * @return String Formatted String.
     */
    public function start(string $string): string;

    public function end(string $string): string;

    public function needsClosing(): bool;

    public function closeForStrategy(FormatStrategy $nextStrategy): bool;
    
    public function openAfterStrategy(FormatStrategy $strategy): bool;
}
