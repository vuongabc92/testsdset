<?php
namespace App\Helpers\Pdfjam;

class Pdfjam {

    protected $command = 'pdfjam';

    protected $pdf;

    public function __construct($pdf) {
        $this->pdf = $pdf;
    }

    public function execute() {
        exec($this->getCommand());
    }

    public function getCommand() {
        return $this->command . ' ' . $this->pdf;
    }

    /**
     * Set pdf margin, negative number means add more margin and the other means minus
     *
     * @param int $top
     * @param int $right
     * @param int $bottom
     * @param int $left
     */
    public function setMargin($top, $right, $bottom, $left) {

        $margin = '';
        foreach ([$left, $bottom, $right, $top] as $one) {
            if ($one > 0) {
                $margin .= "-{$one}cm ";
            } else {
                $margin .= "{$one}cm ";
            }
        }

        $this->command .= " --trim '" . trim($margin) . "'";
    }

    /**
     * Set fitpaper option
     */
    public function setFitpaper() {
        $this->command .= ' --fitpaper true';
    }

    /**
     * Set paper option: a4, letter,...
     *
     * @param string $paper
     */
    public function setPaper($paper = 'letter') {
        $this->command .= ' --paper ' . $paper;
    }

    /**
     * Output
     *
     * @param string $output
     */
    public function setOutput($output = 'ouput.pdf') {
        $this->command .= ' -o ' . $output;
    }

    public function setDefaultOptions() {
        $this->setFitpaper();
        $this->setPaper();
        $this->setMargin(config('frontend.pdfDefaultMargin')/10, 0, 0, 0);
    }

}