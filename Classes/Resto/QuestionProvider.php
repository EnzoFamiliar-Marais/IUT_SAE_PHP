<?php
namespace Classes\Form;

use Classes\Form\Type\Question;
require_once __DIR__ . '/Type/Question.php';



class QuestionProvider {
    private $questions = [];

    public function __construct($file) {
        $this->loadQuestions($file);
    }

    private function loadQuestions($file) {
        $data = json_decode(file_get_contents($file), true);
        foreach ($data as $q) {
            $this->questions[] = new Question(
                $q["name"], 
                $q["type"], 
                $q["text"],
                $q["answer"], 
                $q["score"], 
                $q["choices"] ?? []
            );
        }
    }

    public function getQuestions() {
        return $this->questions;
    }
}
?>
