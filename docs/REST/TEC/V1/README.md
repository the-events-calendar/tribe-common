# TEC Common REST API Documentation

This documentation covers the common REST API components shared across The Events Calendar plugins.

## Table of Contents

- [Interfaces](interfaces.md) - Core interface definitions
- [Abstract Classes](abstract-classes.md) - Base implementations
- [Parameter Types](parameter-types.md) - Type system for parameters
- [Traits](traits.md) - Reusable functionality
- [Controller](controller.md) - Main REST controller

## Overview

The common REST API library provides:

1. **Core Contracts** - Interface definitions for endpoints
2. **Abstract Implementations** - Base classes for common functionality
3. **Type System** - Parameter validation and documentation
4. **OpenAPI Support** - Automatic API documentation generation

## Architecture

```bash
common/src/Common/REST/TEC/V1/
├── Abstracts/          # Base abstract classes
├── Contracts/          # Interface definitions
├── Documentation/      # OpenAPI documentation classes
└── Endpoints/          # Endpoint controllers
├── Parameter_Types/    # Type definitions
├── Traits/             # Reusable traits
└── Controller.php      # Main controller
└── Endpoints.php       # Endpoint controller
```

## Key Components

### Contracts (Interfaces)

Define the capabilities of endpoints:

- `Endpoint_Interface` - Base interface
- `Readable_Endpoint` - GET support
- `Creatable_Endpoint` - POST support
- `Updatable_Endpoint` - PUT/PATCH support
- `Deletable_Endpoint` - DELETE support
- `Collection_Endpoint` - Full CRUD for collections
- `RUD_Endpoint` - Read, Update, Delete for single entities

### Abstract Classes

Provide base implementations:

- `Endpoint` - Base endpoint functionality
- `Post_Entity_Endpoint` - WordPress post-based endpoints

### Parameter Types

Type-safe parameter definitions:

- `Array_Of_Type`
- `Boolean`
- `Date_Time`
- `Date`
- `Definition_Parameter`
- `Email`
- `Entity`
- `Hex_Color`
- `Integer`
- `IP`
- `Number`
- `Positive_Integer`
- `Text`
- `URI`
- `UUID`

### Traits

Reusable functionality:

- `Create_Entity_Response` - Entity creation
- `Delete_Entity_Response` - Entity deleting
- `Read_Archive_Response` - Collection reading
- `Read_Entity_Response` - Entity reading
- `Update_Entity_Response` - Entity updating
