<?php

namespace App\Enums;

enum TaxonomyVocabularyEnum: int
{
    case AnyLevel = 6;
    case ResourceType = 7;
    case ResourceSubject = 8;
    case ResourcePublisher = 9; // Resource Publisher / Author
    case CreativeCommons = 10;
    case UserDistricts = 11;
    case UserProvinces = 12;
    case ResourceLevels = 13;
    case UserAffiliations = 14;
    case UserCountry = 15;
    case UserGender = 16;
    case UserSubjects = 17;
    case UserTeachingLevel = 18;
    case UserWhoDoYouTeach = 19;
    case PrimaryUser = 20;
    case FeaturedResourceCollections = 21;
    case ResourceTranslator = 22;
    case Keywords = 23;
    case ResourceAuthor = 24;
    case EducationalUse = 25;
    case SharePermission = 26;
}
