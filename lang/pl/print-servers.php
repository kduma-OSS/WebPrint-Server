<?php

return [
    'create-new-print-server-label' => 'Utwórz nowy serwer druku',
    'heading' => 'Serwery druku',
    'never_connected_status' => 'Nigdy nie podłączony',
    'no-permissions-to-create-print-server-label' => 'Nie masz uprawnień aby tworzyć nowe serwery druku',
    'no-print-servers-label' => 'Brak serwerów druku',
    'online-status' => 'Podłączony',
    'seen_online_status' => 'Ostatnio widziany :diff_for_humans',
    'heading_create' => 'Utwórz Serwer Druku',
    'heading_show' => 'Serwer Druku',
    'create' => [
        'title' => 'Serwer Druku',
        'description' => 'Podaj dane tworzonego serwera druku',
    ],
    'name_label' => 'Nazwa',
    'update' => [
        'title' => 'Serwer Druku',
        'description' => 'Zaktualizuj dane serwera druku',
    ],
    'delete' => [
        'title' => 'Skasuj Serwer Druku',
        'description' => 'Trwale usuń ten serwer druku',
        'message_disabled' => 'Serwer nie może zostać w tej chwili skasowany. Wpierw skasuj drukarki powiązane z tym serwerem.',
        'button' => 'Skasuj Serwer',
        'message' => 'Po usunięciu serwera wszystkie jego zasoby i dane zostaną trwale usunięte. Przed usunięciem tego serwera pobierz wszelkie dane lub informacje dotyczące tego serwera, które chcesz zachować.',
        'modal' => [
            'title' => 'Skasuj Serwer',
            'content' => 'Czy na pewno chcesz usunąć ten serwer? Po usunięciu serwera wszystkie jego zasoby i dane zostaną trwale usunięte.',
        ],
    ],
    'token' => [
        'title' => 'Generuj Token API',
        'description' => 'Token umożliwi podłączenie WebPrint Service do tego serwera',
        'message' => 'Do instalacji WebPrint Service, będziesz potrzebować token dostępowy. Aby go wygenerować, kliknij poniższy przycisk.',
        'button' => 'Generuj nowy Token Dostępowy',
        'modal-title' => 'Token Serwera Druku',
        'modal-token' => 'Skopiuj nowy token API. Ze względów bezpieczeństwa nie zostanie ponownie wyświetlony.',
        'modal-env' => 'Jeśli potrzebujesz zmienne środowiskowe do skonfigurowania WebPrint Service, użyj następujących:',
        'modal-docker' => 'Jeśli potrzebujesz komendy tworzącej instancję WebPrint Service w dokerze, użyj tej:',
        'overwrite_modal' => [
            'title' => 'Ten Serwer Druku posiada już wygenerowany Token Dostępowy',
            'content' => 'Jeśli zregenerujesz token dostępowy, stary przestanie działać.',
            'button' => 'Regeneruj Token Dostępowy',
            'content_last_used' => 'Poprzedni token był ostatnio używany :last_used.',
        ],
    ],
];
