<?php

/*
 * This file is part of Monsieur Biz' Coliship plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusColishipPlugin\Form;

use MonsieurBiz\SyliusColishipPlugin\Directory\DirectoryInterface;
use MonsieurBiz\SyliusSettingsPlugin\Form\AbstractSettingsType;
use MonsieurBiz\SyliusSettingsPlugin\Form\SettingsTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SettingsType extends AbstractSettingsType implements SettingsTypeInterface
{
    /**
     * @var DirectoryInterface
     */
    private DirectoryInterface $fmtDirectory;

    /**
     * @var DirectoryInterface
     */
    private DirectoryInterface $orderPaymentStateDirectory;

    /**
     * @var DirectoryInterface
     */
    private DirectoryInterface $orderShippingStateDirectory;

    /**
     * @var DirectoryInterface
     */
    private DirectoryInterface $shippingMethodCodeDirectory;

    /**
     * SettingsType constructor.
     *
     * @param DirectoryInterface $fmtDirectory
     * @param DirectoryInterface $orderPaymentStateDirectory
     * @param DirectoryInterface $orderShippingStateDirectory
     * @param DirectoryInterface $shippingMethodCodeDirectory
     */
    public function __construct(
        DirectoryInterface $fmtDirectory,
        DirectoryInterface $orderPaymentStateDirectory,
        DirectoryInterface $orderShippingStateDirectory,
        DirectoryInterface $shippingMethodCodeDirectory
    ) {
        $this->fmtDirectory = $fmtDirectory;
        $this->orderPaymentStateDirectory = $orderPaymentStateDirectory;
        $this->orderShippingStateDirectory = $orderShippingStateDirectory;
        $this->shippingMethodCodeDirectory = $shippingMethodCodeDirectory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addWithDefaultCheckbox(
                $builder,
                'enabled',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            );

        $this->addWithDefaultCheckbox(
                $builder,
                'debug',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            );

        if ($this->isDefaultForm($builder)) {
            $this->addWithDefaultCheckbox(
                $builder,
                'fmtGeneral',
                TextareaType::class, [
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            );

            $this->addWithDefaultCheckbox(
                $builder,
                'paymentState',
                ChoiceType::class, [
                    'required' => true,
                    'choices' => $this->orderPaymentStateDirectory->getValues(),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            );

            $this->addWithDefaultCheckbox(
                $builder,
                'shippingState',
                ChoiceType::class, [
                    'required' => true,
                    'choices' => $this->orderShippingStateDirectory->getValues(),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            );

            $this->addWithDefaultCheckbox(
                $builder,
                'methodCode',
                ChoiceType::class, [
                    'required' => true,
                    'choices' => $this->shippingMethodCodeDirectory->getValues(),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            );
        } else {
            $this->addWithDefaultCheckbox(
                $builder,
                'fmtGeneral',
                TextareaType::class, [
                    'required' => false,
                ]
            );

            $this->addWithDefaultCheckbox(
                $builder,
                'paymentState',
                ChoiceType::class, [
                    'required' => false,
                    'choices' => $this->orderPaymentStateDirectory->getValues(),
                ]
            );

            $this->addWithDefaultCheckbox(
                $builder,
                'shippingState',
                ChoiceType::class, [
                    'required' => false,
                    'choices' => $this->orderShippingStateDirectory->getValues(),
                ]
            );

            $this->addWithDefaultCheckbox(
                $builder,
                'methodCode',
                ChoiceType::class, [
                    'required' => false,
                    'choices' => $this->shippingMethodCodeDirectory->getValues(),
                ]
            );
        }

        $this->addWithDefaultCheckbox(
                $builder,
                'exportFields',
                ChoiceType::class, [
                    'required' => false,
                    'multiple' => true,
                    'choices' => $this->fmtDirectory->getValues(),
                ]
            );
    }
}
