<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Company\Entity\Company;
use App\Domain\Company\Entity\CompanyDomain;
use App\Domain\Company\Entity\CompanyName;
use App\Domain\Customization\Entity\Color\CustomizationColor;
use App\Domain\Customization\Entity\CustomizationName;
use App\Domain\Customization\Entity\File\CustomizationFile;
use App\Domain\DeliveryCompany\Entity\DeliveryCompany;
use App\Domain\DeliveryCompany\ValueObject\DeliveryCompanyName;
use App\Domain\Department\Entity\Department;
use App\Domain\Department\Entity\DepartmentName;
use App\Domain\Document\Entity\DocumentType;
use App\Domain\Document\Entity\DocumentTypeName;
use App\Domain\Document\Entity\DocumentTypePosition;
use App\Domain\Language\Entity\Language;
use App\Domain\Language\Entity\LanguageAcronym;
use App\Domain\Language\Entity\LanguageName;
use App\Domain\Security\ResourceAction\Entity\ResourceAction;
use App\Domain\Security\ResourceActionGroup\Entity\ResourceActionGroup;
use App\Domain\Security\Role\Entity\Role;
use App\Domain\Security\Role\Entity\RoleName;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserEmail;
use App\Domain\User\Entity\UserFirstName;
use App\Domain\User\Entity\UserLastName;
use App\Domain\User\Entity\UserPassword;
use App\Domain\User\Entity\UserUsername;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $manager;

    public static function getGroups(): array
    {
        return ['base_system'];
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadBaseResources();
    }

    private function loadBaseResources(): void
    {
        $deliveryCompany = DeliveryCompany::create(
            new Uuid('99900000-c000-c000-c000-000000000999'),
            new DeliveryCompanyName("Delivery Company1")
        );
        $this->manager->persist($deliveryCompany);
        $this->manager->flush();

        $user1 = $deliveryCompany->registerANewUser(
            new Uuid('00000000-c000-c000-c000-000000000001'),
            new UserFirstName('User1'),
            new UserLastName('yeah'),
            new UserEmail('usertest@4a-side.ninja'),
            new UserUsername('user1-yeah')
        );
        $this->manager->persist($user1);
        $this->manager->flush();
    }
}