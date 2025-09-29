---
description: Share policies template based on PolicyProvider.php from ibexa/share
page_type: reference
---

# Share Policies Template

This template documents the share policies as defined in the PolicyProvider.php from ibexa/share repository.

The Share bundle provides functionality for sharing content and products with other users through various mechanisms including public links.

## Available policies

### Share Module

The share module policies control management of public sharing links.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`share`</nobr> | <nobr>`manage_public_link`</nobr> | Manage public link - Create, update, and delete public sharing links | None |

### Content Sharing

Content sharing policies control the ability to share content items.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`content`</nobr> | <nobr>`share`</nobr> | Share - Share content items with other users or via public links | None |

### Product Sharing

Product sharing policies control the ability to share product information.

| Module | Function | Effect | Possible limitations |
|--------|----------|--------|---------------------|
| <nobr>`product`</nobr> | <nobr>`share`</nobr> | Share - Share product information with other users or via public links | None |

## Policy Details

### Share Module Policies

- **share/manage_public_link**: Allows managing public sharing links including creation, modification, and deletion

### Content Sharing Policies

- **content/share**: Allows sharing content items with other users or through public links

### Product Sharing Policies

- **product/share**: Allows sharing product information with other users or through public links

## Related Documentation

- [Policies](policies.md) - Main policies reference
- [Custom policies](custom_policies.md) - How to create custom policies
- [Limitation reference](limitation_reference.md) - Available limitations
- [Permissions](permissions.md) - Overview of the permissions system