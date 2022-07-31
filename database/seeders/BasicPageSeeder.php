<?php

namespace Database\Seeders;

use App\Models\BasicPage;
use App\Models\Section;
use Illuminate\Database\Seeder;

class BasicPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $image = env('APP_URL').'/'.'images/background.png';

        /////////////////////////////////////////// Create basic pages /////////////////////////////////
        // Published pages //
        BasicPage::create([
            'name' => 'home',
            'slug' => 'home',
            'publication_status' => 1
        ]);
        BasicPage::create([
            'name' => 'about',
            'slug' => 'about',
            'title' => 'ABOUT',
            'subtitle' => 'THE OPEN CRATE',
            'publication_status' => 1
        ]);
        BasicPage::create([
            'name' => 'services',
            'slug' => 'services',
            'title' => 'SERVICES',
            'publication_status' => 1
        ]);
        BasicPage::create([
            'name' => 'clients',
            'slug' => 'clients',
            'title' => 'CLIENTS',
            'subtitle' => 'Lorem ipsum dolor sit amet consectetur.',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec posuere nisi ipsum, eget elementum quam imperdiet tincidunt.',
            'publication_status' => 1
        ]);
        BasicPage::create([
            'name' => 'contact',
            'slug' => 'contact',
            'title' => 'CONTACT',
            'publication_status' => 1
        ]);
        BasicPage::create([
            'name' => 'cultural incubator',
            'slug' => 'cultural-incubator',
            'publication_status' => 1,
            'title' => 'CULTURAL',
            'subtitle' => 'INCUBATOR',
            'description' => 'Our Digital Cutural Incubator allows creatives to hatch their projects on our website'
        ]);
        // Unpublished pages //
        BasicPage::create([
            'name' => 'artists',
            'slug' => 'artists',
            'publication_status' => 0
        ]);
        BasicPage::create([
            'name' => 'collection',
            'slug' => 'collection',
            'publication_status' => 0
        ]);
        BasicPage::create([
            'name' => 'explore',
            'slug' => 'explore',
            'publication_status' => 0
        ]);
        BasicPage::create([
            'name' => 'art munchies',
            'slug' => 'art-munchies',
            'publication_status' => 1,
            'title' => 'ART MUNCHIES',
            'subtitle' => 'Lorem ipsum dolor sit amet consectetur.',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec posuere nisi ipsum, eget elementum quam imperdiet tincidunt.'
        ]);
        /////////////////////////////////////////// Create Sections /////////////////////////////////
        // HomePage Sections //
        Section::create( [
            'name' => 'slider-text',
            'type' => 'slider',
            'body' => ['first_title' => 'UNLOCK THE POTENTIAL OF ', 'second_title' => 'YOUR','last_title'=>'COLLECTION'],
            'page_id'=>1
        ]);
        Section::create( [
            'name' => 'slider-media',
            'type' => 'slider',
            'body' => ['media' => $image],
            'page_id'=>1
        ]);
        Section::create( [
            'name' => 'image-left-text-right',
            'type' => 'image-left-text-right',
            'body' => ['media' => $image,'title' => 'ABOUT','subtitle' => 'THE OPEN CRATE', 'description' => 'A private and secure digital space to manage, share and valorize your collection. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a massa nec eros.'],
            'page_id' => 1
        ]);
        Section::create( [
            'name' => 'slogan',
            'type' => 'slogan',
            'body' => ['title' => 'The open crate change','subtitle' => 'My life', 'name' => 'Foulen Ben Foulen'],
            'page_id' => 1
        ]);
        Section::create( [
            'name' => 'slogan',
            'type' => 'slogan',
            'body' => ['title' => 'The open crate change','subtitle' => 'My life', 'name' => 'Foulen Ben Foulen'],
            'page_id' => 1
        ]);
        Section::create( [
            'name' => 'number-text-left-image-right',
            'type' => 'services',
            'body' => ['title'=>'Digital Inventory','subtitle'=>'& Management System','description'=>'We developed an innovative private digital platform for our clients to enjoy their collection with the highest form of privacy and confidentiality. The Open Crate’s login section is an exclusive online access to your collection through a secure and user-friendly web platform developed by our team of specialists. Our team handles the full inventory and cataloguing of your collection made available to you to maintain, manage and explore your collection. Request access by sending an email to info@theopencrate.com','media'=>$image],
            'page_id' => 1
        ]);
        Section::create( [
            'name' => 'number-text-left-image-right',
            'type' => 'services',
            'body' => ['title'=>'Collection','subtitle'=>' & Inventory Book','description'=>'The Open Crate publishes beautifully curated books on private collections with impactful visuals and key texts to illustrate its depth…','media'=>null],
            'page_id' => 1
        ]);
        Section::create( [
            'name' => 'contact',
            'type' => 'contact',
            'body' => ['title' => 'contact us','description_left' => 'A private and secure digital space to manage, share and valorize your collection.','description_right' => 'Please send us an email on this address or feel in the form below :info@theopencrate.com'],
            'page_id' => 1
        ]);
        // Page About Sections //
        Section::create( [
            'name' => 'text-left-image-right',
            'type' => 'text-left-image-right',
            'body' => ['title'=> 'A private and secure digital space to manage, share and valorize your collection',
                        'subtitle' => null,
                        'description' => 'The Open Crate is a private digital solution for collectors, advisors, cultural institutions and art professionals. We are pioneering the digital inventory space as the first independent digital solution with a focus on the Middle East and Africa. Our mission is to document and valorise private collections in a quest to preserve our cultural legacy and protect its future. Founded by Amina Debbiche and Nora Mansour, this unique approach to collection management let users access, curate and pilot their collection securely and efficiently. From managing your collection to connecting it with the art world at large, The Open Crate makes it happen. Our team handles the full migration of your inventory with our in-house cataloguing methodology. We have developed an innovative platform that meets today’s requirements in an evolving art market. Our philosophy is founded on transparency and discretion and we offer the highest form of privacy and confidentiality.',
                        'media' => $image],
            'page_id' => 2
        ]);
        Section::create( [
            'name' => 'image-left-text-right',
            'type' => 'image-left-text-right',
            'body' => ['title'=> 'Preserve the past | Protect the future',
                        'subtitle' => 'It is a membership only access, please get in touch for further information.',
                        'description' => 'Founded by Amina Debbiche and Nora Mansour, this unique approach to collection management let users access, curate and pilot their collection securely and efficiently. From managing your collection to connecting it with the art world at large, The Open Crate makes it happen. Our team handles the full migration of your inventory with our in-house cataloguing methodology. We have developed an innovative platform that meets today’s requirements in an evolving art market. Our philosophy is founded on transparency and discretion and we offer the highest form of privacy and confidentiality.',
                        'media' => $image],
            'page_id' => 2
        ]);
        Section::create( [
            'name' => 'description-left-image-name-right',
            'type' => 'founders',
            'body' => ['description'=>'Amina Debbiche earned an MA in Finance & Banking in Paris, followed by a PGDip/MA in Modern & Contemporary Art and a Certificate in Art Business at Christie’s Education in London. She then spent several years working and honing her art world experience, from Phillip’s auction house to The Fine Art Fund to Christie’s London, in both, Post-War & Contemporary and Private Sales departments. In 2014, she joined Art Dubai to manage its Patron Circle and Art Salon in the VIP Department and remained there for 2 years prior to the launch of The Open Crate.','media' => $image,'title'=>'Amina','subtitle'=>'Debbiche'],
            'page_id' => 2
        ]);
        Section::create( [
            'name' => 'name-image-left-description-right',
            'type' => 'founders',
            'body' => ['title'=>'Nora','subtitle'=>'Mansour','media' => $image,'description'=>'Prior to co-founding The Open Crate in 2017, Nora worked for four years with Carpenters Workshop Gallery (CWG) allowing her to gain expertise in the field of design and to work at several art fairs including Art Basel. Nora received a BA in Business studies/Banking and finance at the American University of Beirut, followed by a position at PwC (Beirut, Paris and Dubai) for three years. She later joined the Binladin Group in Dubai as manager for the M&A department.'],
            'page_id' => 2
        ]);
        Section::create( [
            'name' => 'contact',
            'type' => 'contact',
            'body' => ['title' => 'contact us','description_left' => 'A private and secure digital space to manage, share and valorize your collection.','description_right' => 'Please send us an email on this address or feel in the form below :info@theopencrate.com'],
            'page_id' => 2
        ]);
        // Page services Sections //
        Section::create( [
            'name' => 'number-text-left-image-right',
            'type' => 'services',
            'body' => ['number'=>'01','title'=>'Digital Inventory','subtitle'=>'& Management System','description'=>'We developed an innovative private digital platform for our clients to enjoy their collection with the highest form of privacy and confidentiality. The Open Crate’s login section is an exclusive online access to your collection through a secure and user-friendly web platform developed by our team of specialists. Our team handles the full inventory and cataloguing of your collection made available to you to maintain, manage and explore your collection. Request access by sending an email to info@theopencrate.com','media'=>$image],
            'page_id' => 3
        ]);
        Section::create( [
            'name' => 'image-left-number-text-right',
            'type' => 'services',
            'body' => ['number'=>'02','title'=>'Collection','subtitle'=>'& Inventory Book','description'=>'The Open Crate publishes beautifully curated books on private collections with impactful visuals and key texts to illustrate its depth. Our team handles the full process including capturing great visuals of your home, high-resolution images of your pieces, conducting interviews and writing tailored texts. We also produce inventory books about your collection and proceed with the cataloguing, archiving, organizing of the information and important documents.','media'=>$image],
            'page_id' => 3
        ]);
        Section::create( [
            'name' => 'contact',
            'type' => 'contact',
            'body' => ['title' => 'contact us','description_left' => 'A private and secure digital space to manage, share and valorize your collection.','description_right' => 'Please send us an email on this address or feel in the form below :info@theopencrate.com'],
            'page_id' => 3
        ]);
        // Page clients sections //
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'MARIAN GOODMAN GALLERY'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'ISAAC JULIEN STUDIO'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'KASMIN'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'ALAN KLINKHOFF GALLERY'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'SHANTELL MARTIN'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'LUXEMBOURG & DAYAN'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'STEPHEN BURGER GALLERY'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'INK STUDIO'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'ROBILANT+VOENA'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'BEN BROWN FINE ARTS'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'DC3 ART PROJECTS'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'clients',
            'type' => 'clients',
            'body' => ['name' => 'MESTARIA'],
            'page_id' => 4
        ]);
        Section::create( [
            'name' => 'contact',
            'type' => 'contact',
            'body' => ['title' => 'contact us','description_left' => 'A private and secure digital space to manage, share and valorize your collection.','description_right' => 'Please send us an email on this address or feel in the form below :info@theopencrate.com'],
            'page_id' => 4
        ]);
        // Page contact sections //
        Section::create( [
            'name' => 'text-left-image-right',
            'type' => 'text-left-image-right',
            'body' => ['title'=> 'UNLOCK THE POTENTIAL OF YOUR COLLECTION',
                        'subtitle' => 'PROTECT LEGACY WITH THE OPEN CRATE',
                        'description' =>'<p>Please send us an email on this address or feel in the form below :</p><p>&nbsp;</p><p>InstagramData : @theopencrate</p>',
                        'media' => $image],
            'page_id' => 5
        ]);
        // Page cultural incubator sections //
        Section::create( [
            'name' => 'contact',
            'type' => 'contact',
            'body' => ['title' => 'contact us','description_left' => 'A private and secure digital space to manage, share and valorize your collection.','description_right' => 'Please send us an email on this address or feel in the form below :info@theopencrate.com'],
            'page_id' => 6
        ]);

    }
}
