# Installationsanleitung (kurz)

```bash
$ git clone https://github.com/hggbroich/bus.git
$ cd bus
$ composer install --no-dev --classmap-authoritative
$ cp .env .env.local
```

Nun die Konfigurationsdatei entsprechend anpassen (siehe bspw. https://docs.schulit.de/sbl/admin/install/configuration).

```bash
$ php bin/console app:create-certificate --type saml
```

Nun im Single Sign-On unter Verwaltung -> IdP-Details den XML-Teil nach `saml/idp.xml` kopieren und abspeichern.

Im Single Sign-On nun einen neuen SchulIT-Dienst hinzufügen und die Autoconfig-URL `https://busticket.example.com/autoconfig`
verwenden.

```bash
$ php bin/console assets:install
$ php bin/console importmap:install
$ php bin/console asset-map:compile
$ php bin/console cache:clear
$ php bin/console doctrine:migrations:migrate --no-interaction
```

Nun im SSO den entsprechenden Benutzern die Rechte zuweisen.
