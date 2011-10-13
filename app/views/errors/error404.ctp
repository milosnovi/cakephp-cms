	<div class="page error404">
		<div class="contents">
			<h2>Strana koju tražite nije nađena</h2>
				<p>Tražena strana nije nađena na serveru. Možda niste u mogućnosti da vidite sadržaj zato što:</p>
			<ul>
				<li><strong>Koristite prečicu koja je istekla</strong></li>
				<li><strong>Ste pogrešno uneli adresu</strong></li>
			</ul>
			<p>Ako mislite da je ovo sistemska greška i da treba da se popravi možete nam poslati izveštaj ovde:</p>
			<form action="/inkoplan/report_bad_link" method="post" style="overflow: hidden">
				<fieldset style="display:none;"><input type="hidden" value="POST" name="_method"></fieldset>
				<input type="hidden" id="ErrorReportBadLink" value=<?=$bad_link?> name="data[ErrorReport][bad_link]">
				<input type="hidden" id="ErrorReportRefererLink" value=<?=$referer_link?> name="data[ErrorReport][referer_link]">
				<div class="submit"><input type="submit" value="Prijavi nepostojeću stranu" class="button"></div>
			</form>
	<br>
<h3>Šta sad?</h3>
<p>Možete:</p>
<ul>
	<li><a href="/inkoplan">Otići na početnu stranu</a></li>
	<li><a href="/inkoplan/search">Probati pretragu sajta</a></li>
	<li><a href="/inkoplan/contact">Kontaktirajte nas i pitajte u vezi onoga što radite</a></li>
</ul></div>
</div>
	