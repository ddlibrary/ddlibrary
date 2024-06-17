<?php

namespace App\Enums;

enum UserRoleEnum: int
{
    case AnonymousUser = 1;
    case AuthenticatedUser = 2;
    case LibraryManager = 3;
    case LibraryTeacher = 4;
    case SiteAdministrator = 5;
    case LibraryUser = 6;
}
