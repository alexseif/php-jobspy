# System Architecture & Philosophy

## Core Philosophy

**Absolute System Motto:** "Breaking the matrix we built."

Our architecture is strictly governed by a **decoupled, local-first database philosophy**. 

1.  **Local Telemetry Core**: SQLite (`database/me.db`) serves as the absolute local telemetry core and the singular source of truth for all LLM ingestion.
2.  **Strict Separation of Concerns**: The local data engine is fully decoupled and isolated from the public frontend static engine (`alexseif.com`). The local environment holds the aggregate intelligence; the public environment serves only finalized, compiled representations.

## Deterministic Data Execution Flow

The system operates on a strictly deterministic pipeline to guarantee precision, reproducibility, and total data control:

1.  **Ingestion**: Raw vacancy and market data is harvested via the `php-jobspy` CLI utility.
2.  **Relational Core Engine Parsing**: Harvested data is structured, normalized, and stored within the local SQLite (`me.db`) engine.
3.  **Local Llama Inference Screening / Context Injection**: The local Llama model reads from the relational engine to perform binary qualification screening and dynamically injects precise historical context based on validated milestones.
4.  **Dynamic ATS Resume Generation**: Contextualized, filtered outputs are compiled into perfectly targeted, ATS-ready Markdown documents.
