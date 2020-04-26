<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Calculation;
use App\Service\DriveService;

class CalculationType extends AbstractType
{
    /**
     * @var DriveService
     */
    private $driveService;

    /**
     * @param DriveService $driveService
     */
    public function __construct(DriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('calculationGroup')
            ->add('title')
            ->add('description')
            ->add('spreadSheetId', ChoiceType::class, [
                'placeholder' => '- Bitte wähle eine Datei aus -',
                'choices'     => $this->driveService->getSpreadSheetList()
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calculation::class,
        ]);
    }
}
