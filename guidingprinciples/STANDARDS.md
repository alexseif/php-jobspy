# Core Development Standards

## Licensing

All core development constraints and generated code within this repository are consolidated under the **Creative Commons Attribution-NonCommercial 4.0 International (CC BY-NC 4.0) License**.

## Tech Stack Blueprint

The ecosystem is built on a rigid, immutable technology stack. No alternatives are permitted:
*   **PHP**: Version 8.2+
*   **Framework Components**: Symfony Components (`HttpClient`, `Console`, `VarDumper`)
*   **Dependency Management**: Composer
*   **Database**: Native SQLite3 PDO drivers

## Strict Code Architecture

### 1. PHP-FIG Compliance
*   **PSR-4**: Strict implementation of autoloading namespaces.
*   **PSR-12**: Full adherence to the Extended Coding Style Guide.

### 2. OOP and Design Principles
*   **Type Declarations**: Every PHP file must declare `declare(strict_types=1);`.
*   **State**: Enforce immutable state objects wherever possible.
*   **Architecture**: Clean separation of concerns and rigorous domain modeling.

### 3. Web UI Standards
*   **Markup**: Valid semantic HTML5.
*   **Naming Conventions**: Strict adherence to `PascalCase` for classes and `snake_case` for fields/methods.
*   **Design**: Mobile-first responsive execution.
*   **Security**: Secure-by-design development encompassing SQL Injection mitigation via forced prepared statements, automated HTML escaping, and absolute context boundaries.

### 4. Database Rigor
*   **Core Operational Data**: Must be normalized strictly to the **3rd Normal Form (3NF)** to eliminate all redundancy.
*   **Analytics Schema**: Optimized using a **Star Schema** layout (Fact and Dimension tables) to facilitate rapid local context extraction for the LLM.

## Ethical Web Scraping Guidelines

All automated data ingestion pipelines must abide by the following ethical constraints:
*   **Robots.txt Compliance**: Strict, unyielding adherence to `robots.txt` directives on target domains.
*   **Rate Limiting**: Mandatory randomized sleep intervals between **3.5 to 7.0 seconds** to bypass behavioral blocks and respect host load.
*   **Transparency**: Explicit, identifiable `User-Agent` strings defining the scraper's purpose.
*   **Resilience**: Implementation of fallback fuzzy selectors to navigate DOM volatility.
*   **Efficiency**: Local raw payload caching to protect network bandwidth and minimize redundant HTTP requests.
