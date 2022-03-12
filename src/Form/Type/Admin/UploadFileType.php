<?php

namespace App\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class UploadFileType extends AbstractType
{
    const VALID_MIME_TYPES = ['text/plain', 'text/csv', 'application/vnd.ms-excel'];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'file',
            FileType::class,
            [
                'label' => 'Import file',
                'required' => true,
                'constraints' => [
                    new File(
                        [
                            'mimeTypes' => self::VALID_MIME_TYPES,
                            'maxSize' => '5M',
                            'mimeTypesMessage' => 'Please upload a valid CSV file',
                        ]
                    ),
                ],
            ]);
    }
}
