<?php

return [
    'create-new-client-application-label' => 'Dodaj nową aplikację',
    'no-client-applications-label' => 'Brak Aplikacji',
    'no-location-label' => 'Brak lokalizacji',
    'no-permissions-to-create-application-label' => 'Nie masz uprawnień do dodawania nowych aplikacji',
    'heading_create' => 'Dodaj nową aplikację kliencką',
    'heading_show' => 'Aplikacja Kliencka',
    'create' => [
        'title' => 'Aplikacja Kliencka',
        'description' => 'Podaj dane tworzonego aplikacji klienckiej',
    ],
    'name_label' => 'Nazwa',
    'update' => [
        'title' => 'Aplikacja Kliencka',
        'description' => 'Zaktualizuj dane aplikacji klienckiej',
    ],
    'delete' => [
        'title' => 'Skasuj Aplikację Kliencką',
        'description' => 'Trwale usuń ten aplikację kliencką',
        'message_disabled' => 'Aplikacja nie może zostać w tej chwili skasowana. Wpierw skasuj powiązane zasoby.',
        'button' => 'Skasuj Aplikację',
        'message' => 'Po usunięciu aplikację wszystkie jego zasoby i dane zostaną trwale usunięte. Przed usunięciem tego aplikację pobierz wszelkie dane lub informacje dotyczące tego aplikację, które chcesz zachować.',
        'modal' => [
            'title' => 'Skasuj Aplikację',
            'content' => 'Czy na pewno chcesz usunąć tą aplikację? Po usunięciu aplikacji wszystkie jej zasoby i dane zostaną trwale usunięte.',
        ],
    ],
    'token' => [
        'title' => 'Generuj Token API',
        'description' => 'Token umożliwi podłączenie do WebPrint API',
        'message' => 'Do połączenia z WebPrint API, będziesz potrzebować token dostępowy. Aby go wygenerować, kliknij poniższy przycisk.',
        'button' => 'Generuj nowy Token Dostępowy',
        'modal-title' => 'Token Aplikacji Klienckiej',
        'modal-token' => 'Skopiuj nowy token API. Ze względów bezpieczeństwa nie zostanie ponownie wyświetlony.',
        'modal-env' => 'Jeśli potrzebujesz zmienne środowiskowe do skonfigurowania połączenia z WebPrint API, użyj następujących:',
        'overwrite_modal' => [
            'title' => 'Ta aplikacja posiada już wygenerowany Token Dostępowy',
            'content' => 'Jeśli zregenerujesz token dostępowy, stary przestanie działać.',
            'button' => 'Regeneruj Token Dostępowy',
            'content_last_used' => 'Poprzedni token był ostatnio używany :last_used.',
        ],
    ],
];
