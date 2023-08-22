
# WZPN-API

## Introduction

This repository contains PHP scripts to retrieve and process football-related data from the Wielkopolski ZPN website (wielkopolskizpn.pl). The scripts provide functionality to fetch upcoming matches, last matches, and league table data for the given season.

## Configuration

Before using the scripts, you need to configure a few parameters to make them work properly.

### 1. Script Files

- `upcoming_match.php`: Script to fetch and display the closest upcoming match.
- `last_match.php`: Script to fetch and display the most recent match.
- `table.php`: Script to fetch and display league table data.

### 2. URL Configuration

In each script (`upcoming_match.php`, `last_match.php`, and `table.php`), you'll find a section where the target URL is set. Update the URL as follows:

$url = 'https://wielkopolskizpn.pl/box/ajax_league_map';

### 3. Extracting ID from URL

In the `table.php` script, the `id` parameter needs to be extracted from the URL. Modify the URL to include a query parameter that includes the ID, for example:

https://wielkopolskizpn.pl/jesien-2023/?id=54515

### 4. License

This project is licensed under the GNU General Public License v3.0. Please review the [LICENSE](LICENSE) file for more details.

## Usage

To use the scripts:

1. Make sure you have PHP installed on your server.
2. Configure the script files as described above.
3. Upload the script files to your web server.
4. Access the scripts through your web browser or make HTTP requests to them.

## Disclaimer

Please be aware that web scraping and data extraction from websites might be subject to legal and ethical considerations. Make sure to comply with the website's terms of use and applicable laws.

## Contributions

Contributions to this project are welcome. If you encounter issues or want to add new features, feel free to submit a pull request.

## License

This project is licensed under the [GNU General Public License v3.0](LICENSE).
