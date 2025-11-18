# TEC Common Documentation

This directory contains documentation for shared components used across The Events Calendar suite of plugins.

## Documented Features

### 1. REST API V1 Common Components

Shared REST API infrastructure used by all TEC plugins.

- **Location**: [REST/TEC/V1/](REST/TEC/V1/)
- **Overview**: Common interfaces, abstracts, traits, and utilities
- **Components**:
  - Interface definitions
  - Abstract base classes
  - Parameter type system
  - Reusable traits
  - Core controller

### 2. Caching

Key-Value Cache API

- **Location**: [Key_Value_Cache](Key_Value_Cache/key-value-cache.md)
- **Overview**: Unified caching interface that uses object caching when available, falling back to a custom database table for persistent storage.

### 3. JSON Packer API

- **Location**: [JSON_Packer](JSON_Packer/json-packer.md)
- **Overview**: Safe representation of any PHP value as a JSON string.
