<?php
/**
 * This function will render language switcher html
 * @param $class (string) - optional
 * @return html
*/
function show_flags($class = '', $dropdown = 0) {
  $html = '';
	if(function_exists('pll_the_languages')) {
		$html .= '
		<p class="language-switcher-header">'.pll_trans('Wybierz język:', true).'</p>
    <div class="language-switcher '.$class.'">';

		if( $dropdown == 0 ) {
			$html .= '<ul class="language-switcher-langs list-unstyled list-inline">';
		}
      
		$html .= pll_the_languages(array(
				'dropdown'                => $dropdown,
				'show_names'              => 1,
				'show_flags'              => 0,
				'display_names_as'        => 'slug', //name or slug
				'hide_if_empty'           => 0,
				'force_home'              => 0,
				'echo'                    => 0,
				'hide_if_no_translation'  => 0,
				'hide_current'            => 0,
				'post_id'                 => NULL,
				'raw'                     => 0
			));

		if( $dropdown == 0 ) {
			$html .= '
				</ul>';
		}

		$html .= '
    </div>
    ';
	}	
	return $html;
}

/**
 * This function will register strings for translation
 * @param n/a
 * @return n/a
*/
if(function_exists('pll_the_languages')) {
	$group = get_bloginfo('name');

	$slogan = get_bloginfo('description');

	$phrases = array(
		$slogan => $slogan,
		'Adres:' => 'Adres:',
		'Poradnia' => 'Poradnia',
		'Usługa' => 'Usługa',
		'- Wybierz z listy -' => '- Wybierz z listy -',
		'Wybierz' => 'Wybierz',
		'Czcionka:' => 'Czcionka:',
		'Kategoria:' => 'Kategoria:',
		'Kategorie' => 'Kategorie',
		'Miejsce:' => 'Miejsce:',
		'Miejsce' => 'Miejsce',
		'Data:' => 'Data:',
		'Daty' => 'Daty',
		'Wyszukano:' => 'Wyszukano:',
		'Wybierz poradnię' => 'Wybierz poradnię',
		'Sprawdź aktualne oferty pracy' => 'Sprawdź aktualne oferty pracy',
		'Więcej' => 'Więcej',
		'Centra Medyczne' => 'Centra Medyczne',
		'Wybierz grupę kontaktu:' => 'Wybierz grupę kontaktu:',
		'Wybierz dział do kontaktu:' => 'Wybierz dział do kontaktu:',
		'Stanowisko:' => 'Stanowisko:',
		'Stanowisko' => 'Stanowisko',
		'Numer referencyjny oferty:' => 'Numer referencyjny oferty:',
		'Firma:' => 'Firma:',
		'Miejsce pracy:' => 'Miejsce pracy:',
		'Miejsce pracy' => 'Miejsce pracy',
		'Aplikuj' => 'Aplikuj',
		'Typ pracy' => 'Typ pracy',
		'Data publikacji' => 'Data publikacji',
		'Data składania apl.' => 'Data składania apl.',
		'Nie znaleziono ofert' => 'Nie znaleziono ofert',
		'Zobacz na mapie' => 'Zobacz na mapie',
		'Nie znaleziono lekarzy' => 'Nie znaleziono lekarzy',
		'Informacje podstawowe' => 'Informacje podstawowe',
		'Godziny otwarcia' => 'Godziny otwarcia',
		'Lokalizacja' => 'Lokalizacja',
		'Nie znaleziono placówek' => 'Nie znaleziono placówek',
		'Grupa usług' => 'Grupa usług',
		'Miasto' => 'Miasto',
		'Rodzaj placówki' => 'Rodzaj placówki',
		'Szukaj usługi...' => 'Szukaj usługi...',
		'zł' => 'zł',
		'Rozwiń' => 'Rozwiń',
		'Cennik' => 'Cennik',
		'Zobacz więcej' => 'Zobacz więcej',
		'Wybierz usługę' => 'Wybierz usługę',
		'Najnowsze wiadomości' => 'Najnowsze wiadomości',
		'Wyszukiwarka' => 'Wyszukiwarka',
		'Zobacz także' => 'Zobacz także',
		'Zobacz wszystkie' => 'Zobacz wszystkie',
		'Czytaj więcej' => 'Czytaj więcej',
		'Strona, której szukasz nie istnieje!' => 'Strona, której szukasz nie istnieje!',
		'Wszelkie prawa zastrzeżone' => 'Wszelkie prawa zastrzeżone',
		'Copyright' => 'Copyright',
		'więcej' => 'więcej',
		'mniej' => 'mniej',
		'Poprzednie' => 'Poprzednie',
		'Następne' => 'Następne',
		'Nic nie znaleziono' => 'Nic nie znaleziono',
		'Szukaj...' => 'Szukaj...',
		'Znajdź placówkę!' => 'Znajdź placówkę!',
		'Centrum medyczne' => 'Centrum medyczne',
		'Wybierz centrum medyczne' => 'Wybierz centrum medyczne',
		'Wybierz miasto' => 'Wybierz miasto',
		'Szukaj na mapie' => 'Szukaj na mapie',
		'Wybierz miasto z listy...' => 'Wybierz miasto z listy...',
		'Adres' => 'Adres',
		'Wybierz adres' => 'Wybierz adres',
		'Przejdź do placówki' => 'Przejdź do placówki',
		'Specializacja:' => 'Specializacja:',
		'Kategorie' => 'Kategorie',
		'Do koszyka' => 'Do koszyka',
		'Wszystkie' => 'Wszystkie',
		'Cena' => 'Cena',
		'od' => 'od',
		'do' => 'do',
		'Zapisz się' => 'Zapisz się',
		'Kup teraz' => 'Kup teraz',
		'zł' => 'zł',
		'PLN' => 'PLN',
		'Zaloguj' => 'Zaloguj',
		'Załóż konto' => 'Załóż konto',
		'Produkt' => 'Produkt',
		'został dodany do koszyka' => 'został dodany do koszyka',
		'Wystąpił błąd. Odśwież stronę i spróbuj ponownie.' => 'Wystąpił błąd. Odśwież stronę i spróbuj ponownie.',
		'Producent:' => 'Producent:',
		'Kod produktu:' => 'Kod produktu:',
		'Ilość:' => 'Ilość:',
		'produkt' => 'produkt',
		'produkty' => 'produkty',
		'produktów' => 'produktów',
		'Koszyk' => 'Koszyk',
		'Koszyk jest pusty' => 'Koszyk jest pusty',
		'Wróć do sklepu' => 'Wróć do sklepu',
		'Przejdź do koszyka' => 'Przejdź do koszyka',
		'Ilość' => 'Ilość',
		'Suma' => 'Suma',
		'Usuń' => 'Usuń',
		'Aktualizuj koszyk' => 'Aktualizuj koszyk',
		'Podsumowanie' => 'Podsumowanie',
		'Razem' => 'Razem',
		'Podatek VAT' => 'Podatek VAT',
		'Do zapłaty' => 'Do zapłaty',
		'zawiera' => 'zawiera',
		'VAT' => 'VAT',
		'Przejdź do kasy' => 'Przejdź do kasy',
		'Zamówienie' => 'Zamówienie',
		'Moje konto' => 'Moje konto',
		'Zapisz' => 'Zapisz',
		'Dane adresowe' => 'Dane adresowe',
		'Zmiany zostały zapisane' => 'Zmiany zostały zapisane',
		'Osoba fizyczna' => 'Osoba fizyczna',
		'Firma' => 'Firma',
		'Dane osobowe' => 'Dane osobowe',
		'Nazwa firmy' => 'Nazwa firmy',
		'Imię' => 'Imię',
		'Nazwisko' => 'Nazwisko',
		'Podaj imię' => 'Podaj imię',
		'Podaj nazwisko' => 'Podaj nazwisko',
		'Podaj nazwę firmy' => 'Podaj nazwę firmy',
		'NIP firmy' => 'NIP firmy',
		'Podaj nr NIP' => 'Podaj nr NIP',
		'Dane kontaktowe' => 'Dane kontaktowe',
		'Telefon' => 'Telefon',
		'Podaj nr telefonu' => 'Podaj nr telefonu',
		'Email' => 'Email',
		'Podaj adres email' => 'Podaj adres email',
		'Dane adresowe zamawiającego' => 'Dane adresowe zamawiającego',
		'Ulica' => 'Ulica',
		'Podaj ulicę' => 'Podaj ulicę',
		'Numer lokalu' => 'Numer lokalu',
		'Podaj nr lokalu / domu' => 'Podaj nr lokalu / domu',
		'Miasto' => 'Miasto',
		'Podaj miasto' => 'Podaj miasto',
		'Kod pocztowy' => 'Kod pocztowy',
		'Podaj kod pocztowy' => 'Podaj kod pocztowy',
		'Kraj' => 'Kraj',
		'Podaj kraj' => 'Podaj kraj',
		'Adres dostawy' => 'Adres dostawy',
		'Adres dostawy jest inny niż adres zamawiającego' => 'Adres dostawy jest inny niż adres zamawiającego',
		'Płacę przelewem' => 'Płacę przelewem',
		'Płacę online' => 'Płacę online',
		'Nieprawidłowe id zamówienia. Dostęp zablokowany.' => 'Nieprawidłowe id zamówienia. Dostęp zablokowany.',
		'Data zamówienia' => 'Data zamówienia',
		'Numer zamówienia' => 'Numer zamówienia',
		'Wartość zamówienia' => 'Wartość zamówienia',
		'Status zamówienia' => 'Status zamówienia',
		'Akcje' => 'Akcje',
		'Faktura' => 'Faktura',
		'Szczegóły' => 'Szczegóły',
		'Sposób płatności' => 'Sposób płatności',
		'Przelew' => 'Przelew',
		'Płatność online' => 'Płatność online',
		'Sposób wysyłki' => 'Sposób wysyłki',
		'Imię i nazwisko' => 'Imię i nazwisko',
		'Nazwa firmy' => 'Nazwa firmy',
		'NIP firmy' => 'NIP firmy',
		'ul.' => 'ul.',
		'Adres email' => 'Adres email',
		'Numer telefonu' => 'Numer telefonu',
		'Zamówienie nr' => 'Zamówienie nr',
		'Ogólne informacje' => 'Ogólne informacje',
		'Szczegóły klienta' => 'Szczegóły klienta',
		'Szczegóły wysyłki' => 'Szczegóły wysyłki',
		'Wysyłka na adres klienta' => 'Wysyłka na adres klienta',
		'Koszt' => 'Koszt',
		'Wysyłka' => 'Wysyłka',
		'Numer NIP' => 'Numer NIP',
		'Dostawa' => 'Dostawa',
		'Kontynuuj zakupy' => 'Kontynuuj zakupy',
		'Twoje zamówienie zostało anulowane' => 'Twoje zamówienie zostało anulowane',
		'Kod aktywacyjny pakietu' => 'Kod aktywacyjny pakietu',
		'Nieprawidłowy token' => 'Nieprawidłowy token',
		'Specjalizacja' => 'Specjalizacja'
  );

	foreach($phrases as $name => $phrase) {
		pll_register_string( $name, $phrase, $group );
	}
}

/**
 * This function will return or echo translated strings
 * @param $string (string)
 * @param $return (boolean) - optional
 * @return string
*/
function pll_trans($string, $return = false) {
	if(function_exists('pll_the_languages')) {
		if( $return ) {
			return pll__( $string );	
		}else {
			return pll_e( $string );	
		}
	}else {
		if( $return ) {
			return $string;	
		}else {
			echo $string;	
		}	
	}
}

/**
 * This function will return current language slug (code)
 * @param n/a
 * @return string
*/
function current_lang() {
	if(function_exists('pll_the_languages')) {
		$lang = pll_current_language();
	}else {
		$lang = 'pl';
	}
	return $lang;
}
?>