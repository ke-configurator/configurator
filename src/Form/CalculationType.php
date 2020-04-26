<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Calculation;
use App\Entity\SpreadSheet;
use App\Service\GoogleDriveService;

class CalculationType extends AbstractType
{
    /**
     * @var GoogleDriveService
     */
    private $driveService;

    /**
     * @param GoogleDriveService $driveService
     */
    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('calculationGroup')
            ->add('title')
            ->add('description')
            ->add('spreadSheet', EntityType::class, [
                'placeholder' => '- Bitte wähle eine Datei aus -',
                'class'       => SpreadSheet::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calculation::class,
        ]);
    }
}
