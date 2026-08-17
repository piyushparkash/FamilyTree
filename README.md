# FamilyTree (Vanshavali)

![CodeQL](https://github.com/piyushparkash/FamilyTree/actions/workflows/codeql-analysis.yml/badge.svg)
![License](https://img.shields.io/github/license/piyushparkash/FamilyTree)

A PHP web app for building and visualizing family trees — add and update family members, explore the tree interactively, and let relatives trace their roots.

**Demo:** [familytree.ratupar.in](http://familytree.ratupar.in)

## Features

- Add/update family member records
- Interactive family tree visualization
- Relation lookup between any two members
- Optional WordPress OAuth login (via [WordPress OAuth Server](https://wordpress.org/plugins/oauth2-provider/) + [REST API](https://wordpress.org/plugins/rest-api/) plugins) alongside standard login

## Installation

```bash
git clone https://github.com/piyushparkash/FamilyTree.git
cd FamilyTree
composer install
```

1. Point your web server (Apache/Nginx) at the cloned directory.
2. Grant write permissions to the directories that need them:
   ```bash
   chmod -R 744 directory_name/
   ```
3. Open the app in your browser — first run walks you through database setup. Have these ready:
   - Host (e.g. `localhost`)
   - Username / password
   - Database name
4. (Optional) To enable WordPress login, install the [OAuth Server plugin](https://wordpress.org/plugins/oauth2-provider/) on a WordPress site and provide FamilyTree with the Client ID, Secret Key, and Auth/Token endpoints. The redirect URI is shown during FamilyTree's own setup.

A `Dockerfile` is also included if you'd rather run it containerized.

## Contributing / Issues

Found a bug or have a feature request? [Open an issue](https://github.com/piyushparkash/FamilyTree/issues) — contributions welcome.

## License

[GPL-3.0](LICENSE)

