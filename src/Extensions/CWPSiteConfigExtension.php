<?php

namespace CWP\AgencyExtensions\Extensions;

use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FileHandleField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Versioned\Versioned;

/**
 * Class CWPCleanupSiteConfigExtension
 */
class CWPSiteConfigExtension extends DataExtension
{
    private static $db = array(
        'FooterLogoLink' => 'Varchar(255)',
        'FooterLogoDescription' => 'Varchar(255)',
        'FooterLogoSecondaryLink' => 'Varchar(255)',
        'FooterLogoSecondaryDescription' => 'Varchar(255)',
        'EmptySearch' => 'Varchar(255)',
        'NoSearchResults' => 'Varchar(255)'
    );

    private static $has_one = array(
        'Logo' => Image::class,
        'LogoRetina' => Image::class,
        'FooterLogo' => Image::class,
        'FooterLogoRetina' => Image::class,
        'FooterLogoSecondary' => Image::class,
        'FavIcon' => File::class,
        'AppleTouchIcon144' => File::class,
        'AppleTouchIcon114' => File::class,
        'AppleTouchIcon72' => File::class,
        'AppleTouchIcon57' => File::class
    );

    private static $owns = [
        'Logo',
        'LogoRetina',
        'FooterLogo',
        'FooterLogoRetina',
        'FooterLogoSecondary',
        'FavIcon',
        'AppleTouchIcon144',
        'AppleTouchIcon114',
        'AppleTouchIcon72',
        'AppleTouchIcon57'
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $this
            ->addLogosAndIcons($fields)
            ->addSearchOptions($fields);
    }

    /**
     * Add fields for logo and icon uploads
     *
     * @param  FieldList $fields
     * @return $this
     */
    protected function addLogosAndIcons(FieldList $fields)
    {
        $logoTypes = array('jpg', 'jpeg', 'png', 'gif');
        $iconTypes = array('ico');
        $appleTouchTypes = array('png');

        $fields->findOrMakeTab(
            'Root.LogosIcons',
            _t(__CLASS__ . '.LogosIconsTab', 'Logos/Icons')
        );

        $fields->addFieldToTab(
            'Root.LogosIcons',
            $logoField = Injector::inst()->create(
                FileHandleField::class,
                'Logo',
                _t(__CLASS__ . '.LogoUploadField', 'Logo, to appear in the top left')
            )
        );
        $logoField->getValidator()->setAllowedExtensions($logoTypes);

        $fields->addFieldToTab(
            'Root.LogosIcons',
            $logoRetinaField = Injector::inst()->create(
                FileHandleField::class,
                'LogoRetina',
                _t(
                    'CwpConfig.LogoRetinaUploadField',
                    'High resolution logo, to appear in the top left ' .
                    '(recommended to be twice the height and width of the standard logo)'
                )
            )
        );
        $logoRetinaField->getValidator()->setAllowedExtensions($logoTypes);

        $fields->addFieldToTab(
            'Root.LogosIcons',
            $footerLogoField = Injector::inst()->create(
                FileHandleField::class,
                'FooterLogo',
                _t(__CLASS__ . '.FooterLogoField', 'Footer logo, to appear in the footer')
            )
        );
        $footerLogoField->getValidator()->setAllowedExtensions($logoTypes);

        $fields->addFieldToTab(
            'Root.LogosIcons',
            $footerLogoRetinaField = Injector::inst()->create(
                FileHandleField::class,
                'FooterLogoRetina',
                _t(
                    'CwpConfig.FooterLogoRetinaField',
                    'High resolution footer logo (recommended twice the height and width of the standard footer logo)'
                )
            )
        );
        $footerLogoRetinaField->getValidator()->setAllowedExtensions($logoTypes);

        $fields->addFieldToTab(
            'Root.LogosIcons',
            $footerLink = TextField::create(
                'FooterLogoLink',
                _t(__CLASS__ . '.FooterLogoLinkField', 'Footer Logo link')
            )
        );
        $footerLink->setRightTitle(
            _t(
                'CwpConfig.FooterLogoLinkDesc',
                'Please include the protocol (ie, http:// or https://) unless it is an internal link.'
            )
        );

        $fields->addFieldToTab(
            'Root.LogosIcons',
            TextField::create(
                'FooterLogoDescription',
                _t(__CLASS__ . '.FooterLogoDescField', 'Footer Logo description')
            )
        );

        $fields->addFieldToTab(
            'Root.LogosIcons',
            $footerLogoSecondaryField = Injector::inst()->create(
                FileHandleField::class,
                'FooterLogoSecondary',
                _t(__CLASS__ . '.FooterLogoSecondaryField', 'Secondary Footer Logo, to appear in the footer.')
            )
        );
        $footerLogoSecondaryField->getValidator()->setAllowedExtensions($logoTypes);

        $fields->addFieldToTab('Root.LogosIcons', $footerSecondaryLink = TextField::create(
            'FooterLogoSecondaryLink',
            _t(__CLASS__ . '.FooterLogoSecondaryLinkField', 'Secondary Footer Logo link.')
        ));
        $footerSecondaryLink->setRightTitle(_t(
            'CwpConfig.FooterLogoSecondaryLinkDesc',
            'Please include the protocol (ie, http:// or https://) unless it is an internal link.'
        ));
        $fields->addFieldToTab('Root.LogosIcons', TextField::create(
            'FooterLogoSecondaryDescription',
            _t(__CLASS__ . '.FooterLogoSecondaryDescField', 'Secondary Footer Logo description')
        ));

        $fields->addFieldToTab(
            'Root.LogosIcons',
            $favIconField = Injector::inst()->create(
                FileHandleField::class,
                'FavIcon',
                _t(__CLASS__ . '.FavIconField', 'Favicon, in .ico format, dimensions of 16x16, 32x32, or 48x48')
            )
        );
        $favIconField->getValidator()->setAllowedExtensions($iconTypes);

        $fields->addFieldToTab(
            'Root.LogosIcons',
            $atIcon144 = Injector::inst()->create(
                FileHandleField::class,
                'AppleTouchIcon144',
                _t(
                    'CwpConfig.AppleIconField144',
                    'Apple Touch Web Clip and Windows 8 Tile Icon (dimensions of 144x144, PNG format)'
                )
            )
        );
        $atIcon144->getValidator()->setAllowedExtensions($appleTouchTypes);

        $fields->addFieldToTab(
            'Root.LogosIcons',
            $atIcon114 = Injector::inst()->create(
                FileHandleField::class,
                'AppleTouchIcon114',
                _t(__CLASS__ . '.AppleIconField114', 'Apple Touch Web Clip Icon (dimensions of 114x114, PNG format)')
            )
        );
        $atIcon114->getValidator()->setAllowedExtensions($appleTouchTypes);

        $fields->addFieldToTab(
            'Root.LogosIcons',
            $atIcon72 = Injector::inst()->create(
                FileHandleField::class,
                'AppleTouchIcon72',
                _t(__CLASS__ . '.AppleIconField72', 'Apple Touch Web Clip Icon (dimensions of 72x72, PNG format)')
            )
        );
        $atIcon72->getValidator()->setAllowedExtensions($appleTouchTypes);

        $fields->addFieldToTab(
            'Root.LogosIcons',
            $atIcon57 = Injector::inst()->create(
                FileHandleField::class,
                'AppleTouchIcon57',
                _t(__CLASS__ . '.AppleIconField57', 'Apple Touch Web Clip Icon (dimensions of 57x57, PNG format)')
            )
        );
        $atIcon57->getValidator()->setAllowedExtensions($appleTouchTypes);

        return $this;
    }

    /**
     * Add user configurable search field labels
     *
     * @param  FieldList $fields
     * @return $this
     */
    protected function addSearchOptions(FieldList $fields)
    {
        $fields->findOrMakeTab('Root.SearchOptions');

        $fields->addFieldToTab(
            'Root.SearchOptions',
            TextField::create(
                'EmptySearch',
                _t(
                    'CWP.SITECONFIG.EmptySearch',
                    'Text to display when there is no search query'
                )
            )
        );
        $fields->addFieldToTab(
            'Root.SearchOptions',
            TextField::create(
                'NoSearchResults',
                _t(
                    'CWP.SITECONFIG.NoResult',
                    'Text to display when there are no results'
                )
            )
        );

        return $this;
    }

    /**
     * Auto-publish any images attached to the SiteConfig object if it's not versioned. Versioned objects will
     * handle their related objects via the "owns" API by default.
     */
    public function onAfterWrite()
    {
        if (!$this->owner->hasExtension(Versioned::class)) {
            $this->owner->publishRecursive();
        }
    }
}
