---
description: Template for documenting policies for Ibexa DXP modules and bundles.
page_type: reference
---

# Policies Template

This template provides a standardized format for documenting policies for Ibexa DXP modules and bundles.
Each bundle or module that introduces new policies should follow this structure to maintain consistency across the documentation.

## Template Structure

Use the following structure when documenting policies for a specific module or bundle:

### Module/Bundle Overview

Provide a brief description of what the module/bundle does and why policies are needed for it.

### Available policies

Document each policy module with the following format:

#### Module Name

Brief description of what this policy module controls.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`module_name`</nobr> | <nobr>`function_name`</nobr> | Description of what this function allows | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation) |
|                            | <nobr>`another_function`</nobr> | Description of another function | [Owner](limitation_reference.md#owner-limitation) |

## Template Guidelines

When creating policy documentation:

1. **Module naming**: Use lowercase names with underscores for module names
2. **Function naming**: Use descriptive function names that clearly indicate the action
3. **Effects**: Provide clear, concise descriptions of what each function allows
4. **Limitations**: Link to existing limitations in the limitation reference or document new ones
5. **Table formatting**: Use `<nobr>` tags around module and function names to prevent line breaks
6. **Consistency**: Follow the same structure and formatting as the main [policies.md](policies.md) file

## Example Usage

Here's an example of how to document policies for a hypothetical "content_collaboration" module:

#### Content Collaboration

Policies for managing collaborative content editing features.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`content_collaboration`</nobr> | <nobr>`create_session`</nobr> | create new collaboration sessions | [Content type](limitation_reference.md#content-type-limitation)</br>[Section](limitation_reference.md#section-limitation) |
|                                      | <nobr>`invite_users`</nobr> | invite users to collaboration sessions | [Owner](limitation_reference.md#owner-limitation) |
|                                      | <nobr>`manage_permissions`</nobr> | manage collaboration session permissions | [Owner](limitation_reference.md#owner-limitation) |

## Integration with Main Policies Documentation

After creating policy documentation using this template:

1. Add the new policies to the main [policies.md](policies.md) file in the appropriate section
2. Update the table of contents if needed
3. Ensure all limitation references are properly linked
4. Test that the documentation builds correctly

## Related Documentation

- [Policies](policies.md) - Main policies reference
- [Custom policies](custom_policies.md) - How to create custom policies
- [Limitation reference](limitation_reference.md) - Available limitations
- [Permissions](permissions.md) - Overview of the permissions system