# SymfonyOpenWeatherMap

ZADANIE REKRUTACYJNE

Specyfikacja:
1. Strona główna z wdrożoną mapą Google Maps na takiej zasadzie, aby po kliknięciu na dowolny obszar
następowała akcje:
a. Na podstawie wybranych koordynacji geograficznych następuje komunikacja z API
http://openweathermap.org i pobierana jest aktualna pogoda dla wybranego miejsca (temperatura,
zachmurzenie, wiatr, ogólna informacja „description”).
b. Wynik zapisywany jest do bazy (łącznie z wybranym obszarem lat/lng oraz nazwą wybranego
miasta) i czasem wyszukiwania.
c. Wyświetlenie powyższych informacji w odrębnym modalu, który jest tworzony w momencie, gdy
dane zostały zapisane. Całość powinna działać w tle (bez przeładowania). Podczas zapisywania
powinno być zabezpieczenie strony loaderem, informującym użytkownika, że trwa zapisywanie
2. Historia wyszukiwań – odrębna podstrona, na której będzie:
a. Lista ostatnich wyszukiwań pogody zawierająca wszystkie informacje, np. w formie tabelki z
ograniczeniem wyświetlania 10 rekordów per podstrona (paginacja).
b. Ogólne statystyki z wszystkich pomiarów:
− Minimalna, maksymalna oraz średnia temperatura.
− Najczęściej wyszukiwanie miasto.
− Ilość łącznych wyszukiwań.

Dodatkowe informacje / wymagania:
1. Projekt postawiony na frameworku Symfony 3/4.
2. Po stronie front-endu można zastosować dowolne rozwiązanie, ale najmilej widziany jest React.js.
3. Prezencja ma drugorzędne znacznie, można zastosować dowolny gotowy template.

Na co należy zwrócić uwagę / co punktujemy?
Najistotniejsza jest jakość kodu back-endowego oraz JavaScript. Punktujemy przede wszystkim następujące
obszary:
− Zgodność z briefem.
− Jakość kodu PHP.
− Jakość kodu w JavaScript.
− Podejście do architektury.
− Stosowanie dobrych praktyk programowania.
− Stosowanie wzorców projektowych frameworka Symfony.
− Właściwa forma przekazania efektów zadania.

Forma przekazania efektów zadania (wymagane są obie)
1. Postawienie i instalacja projektu na dowolnym hostingu i przekazanie adresu URL, który umożliwia
uruchomienie projektu w przeglądarce.

2. Prywatne repozytorium GitHub lub GitLab zawierające kod źródłowy i jego udostępnienie na adres ....
