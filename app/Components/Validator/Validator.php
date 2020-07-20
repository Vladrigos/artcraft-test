<?php

namespace App\Components\Validator;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use App\Models\User;
use Symfony\Component\Validator\Validation;

class Validator
{
    public $errors = [];

    private $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    //get all errors
    public function validate(array $filters) : array
    {
        foreach ($filters as $value => $filter)
        {
            $this->$filter($value);
        }
        return $this->errors;
    }

    protected function name($name) : void
    {
        $violations = $this->validator->validate($name, array(
            new Assert\Length(array('min' => 2)),
            new Assert\NotBlank(),
        ));

        if (0 !== count($violations))
        {
            // есть ошибки, теперь вы можете их отобразить
            foreach ($violations as $violation)
            {
                $this->errors['name'][] = $violation->getMessage();
            }
        }
    }

    protected function email($email) : void
    {
        $violations = $this->validator->validate($email, array(
            new Assert\Length(array('min' => 5)),
            new Assert\Email(),
            new Assert\NotBlank(),
        ));

        if(User::where('email', '=', $email)->count() > 0)
        {
            $this->errors['email'][] = 'This email has already been registered!';
        }

        if (0 !== count($violations))
        {
            foreach ($violations as $violation)
            {
                $this->errors['email'][] = $violation->getMessage();
            }
        }
    }

    protected function image($file) : void
    {
        $violations = $this->validator->validate($file, array(
            new Assert\Image([
                'minWidth' => 200,
                'maxWidth' => 600,
                'minHeight' => 200,
                'maxHeight' => 600,
            ]),
            new Assert\File(array(
                'maxSize' => '2048k',
                'mimeTypes' => array(
                    'image/png',
                    'image/JPEG',
                    'image/jpg',
                ),
                'mimeTypesMessage' => 'Please upload a valid png/jpeg/jpg',
            )),
        ));

        if (0 !== count($violations))
        {
            foreach ($violations as $violation)
            {
                $this->errors['file'][] = $violation->getMessage();
            }
        }
    }
}