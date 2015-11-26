Flux Capacitor
==============

Additional integrations with the TYPO3 FormEngine. Includes a default implementation which turns TYPO3 FlexForms into an IRRE
relation that gets edited in approximately the same way but whose data gets stored as fully relational records. Includes Traits
for common class types like controllers and ViewHelpers to consume the related records as a data array.

Development status
------------------

**Status: EXPERIMENTAL** - use at your own risk.

- [x] Converting FlexForm DS to IRRE
- [x] Cloning TCEforms instructions as dynamic TCA for IRRE value records
- [ ] Creating cached data as JSON per sheet
- [ ] Trait for Controller to consume IRRE values as settings array (partially complete)
- [ ] Trait for ViewHelpers to consume IRRE values as settings array
- [ ] API to retrieve individual settings by dotted path using ideal method

Purpose
-------

FluxCapacitor is designed to sit between your code and the TYPO3 FormEngine with one specific purpose: manipulating the data
structure (TCA) before it is handed off to the TYPO3 core. FluxCapacitor consists of:

* The infrastructure to deliver "Implementations" that decide what happens to the FormEngine.
* One type of sub-class declared by interface, which must be returned by Implementations and contain methods to facilitate
  conversion between the Implementation's desired data/structure and FormEngine data/structure.
* Exactly one functioning Implementation with a very specific purpose.

An Implementation is simply a class following a few rules (an interface must be implemented) which can then be registered with
this package and become resolved when it matches a table/field/record.

The package includes a single Implementation which solves a very pertinent problem in TYPO3, on an opt-in basis. Read more below.

The included Implementation
---------------------------

The Implementation included in this package serves a very specific purpose that is considered necessary enough to include as a
default: to convert FlexForm type fields to IRRE (inline relational record editing) which avoids the XML storage engine currently
used to process and store FlexForm data. Internally and for compatibility reasons, and as far as the data types allow this, a copy
of the old type XML gets stored in the field as usual.

Still optional, but requested very often in Flux, this package and the Implementation allows you to first of all only use this
package on sites that you know will support it - and secondly to only use the Implementation on those tables and fields that you
know will supprt it.

The Implementation consists of:

* A `FlexFormConverter` which follows the rules set by the 
* A `ControllerTrait` for you to implement in controllers, which when implemented modifies the reading of `$this->settings` to
  use the `FlexFormConverter`, causing the array to fill with data from the related records instead of XML.
* A model and schema dedicated to storing data as relational records, with a very limited set of TCA (that gets expanded by
  the `FlexFormConverter` to reflect the original data source).
