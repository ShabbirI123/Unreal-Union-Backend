# Unreal Union Backend

## Übersicht
Dieses Dokument beschreibt den Prozess um das Backend lokal mithilfe von Laravel laufen zu lassen.

## Voraussetzungen
Bevor Sie beginnen, stellen Sie sicher, dass folgende Voraussetzungen erfüllt sind:

- php version 8 oder höher ist auf Ihrem System installiert.
- Für Xampp (für Windows) oder Mamp (für MacOS) ist auf Ihrem System installiert.
- Composer version 2 oder höher ist auf Ihrem System installiert.
- npm version 9.8 und Node.js mit version 18 oder höher ist auf Ihrem System installiert.

## Server Konfiguration
- Navigieren Sie im Terminal zum root des heruntergeladenen Projektes
- Installieren Sie Laravel `composer install` und `npm install`
- Erstellen Sie eine .env Datei in dem Sie die vorhandene .env.example kopieren mithilfe von `cp .env.example .env`
- Führen sie folgendes Befehle aus: `php artisan key:generate`
- Starte Xampp/Mamp und öffne phpAdmin auf Ihren Browser. Erstellen Sie dort eine neue Datenbank und benennen Sie diese.
- In Ihrer .env Datei, geben Sie den Namen Ihrer vorhin erstellten Datenbank sowie Ihre Datenbank Zugriffsdaten, damit Laravel auf Ihre Datenbank zugreifen kann.
- Sie sollten nun in der Lage sein, den Server mithilfe von `php artisan serve` zu starten

## Datenbank Konfiguration
Um die Tabellen für Ihre Datenbank aufzusetzen, führen Sie den folgenden Befehl aus: `php artisan migrate`

Sie sollten nun die erstellten Tabellen in Ihrer Datenbank sehen. Diese sind derzeit leer.

Führen Sie folgenden Befehl aus, um Ihre Datenbank mit mock Daten zu füllen: `php artisan db:seed`

Ihre Datenbank sollte nun mit zufällig generierten Daten befüllt sein.

Sie können den Server nun mit `php artisan serve` starten.
