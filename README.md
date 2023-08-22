
# WZPN-API

## Introduction

This repository contains PHP scripts to retrieve and process football-related data from the WZPN (wielkopolskizpn.pl). The scripts provide functionality to fetch upcoming matches, last matches, and league table data for the given season.

## Configuration

Before using the scripts, you need to configure a few parameters to make them work properly.

### 1. Script Files

- `upcoming_match.php`: Script to fetch and display the closest upcoming match.
- `last_match.php`: Script to fetch and display the most recent match.
- `table.php`: Script to fetch and display league table data.

### 2. Team Configuration

In two team focused scripts (`upcoming_match.php` and `last_match.php`), you'll find strings where the target team is referenced. Update the strings to contain your team name in uppercase.

### 3. ID Configuration.

In the `table.php` script, the `id` parameter needs to be extracted from the URL. Modify the URL to include a query parameter that includes the ID, for example:

`https://wielkopolskizpn.pl/jesien-2023/#54143`

In the team focused scripts (`upcoming_match.php` and `last_match.php`) you will need to specify the `id` parameter yourself, do it in the POST request body.

### 4. Season Configuration.

In each script you will need to configure the season that you are pulling the data from, do it in the POST request body.

## Usage

To use the scripts:

1. Make sure you have PHP installed on your server.
2. Configure the script files as described above.
3. Upload the script files to your web server.
4. Access the scripts through your web browser or make HTTP/S requests to them.

## Disclaimer

Please be aware that web scraping and data extraction from websites might be subject to legal and ethical considerations. Make sure to comply with the website's terms of use and applicable laws.

## Contributions

Contributions to this project are welcome. If you encounter issues or want to add new features, feel free to submit a pull request.

## License

This project is licensed under the [GNU General Public License v3.0](LICENSE).
