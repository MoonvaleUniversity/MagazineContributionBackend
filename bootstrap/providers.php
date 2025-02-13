<?php

return [
    App\Providers\AppServiceProvider::class,
    Modules\AcademicYear\App\Providers\AcademicYearServiceProvider::class,
    Modules\Article\App\Providers\ArticleServiceProvider::class,
    Modules\Contribution\App\Providers\ContributionServiceProvider::class,
    Modules\ClosureDate\App\Providers\ClosureDateServiceProvider::class,
    Modules\Faculty\App\Providers\FacultyServiceProvider::class,
    Modules\Users\Admin\App\Providers\AdminServiceProvier::class,
    Modules\Users\Coordinator\App\Providers\CoordinatorServiceProvier::class,
    Modules\Users\Guest\App\Providers\GuestServiceProvier::class,
    Modules\Users\Manager\App\Providers\ManagerServiceProvier::class,
    Modules\Users\Student\App\Providers\StudentServiceProvier::class
];
