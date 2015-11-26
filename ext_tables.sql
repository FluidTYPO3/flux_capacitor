#
# Table structure for table 'tx_fluxcapacitor_domain_model_sheet'
#
CREATE TABLE tx_fluxcapacitor_domain_model_sheet (
        uid int(11) NOT NULL auto_increment,
        pid int(11) DEFAULT '0' NOT NULL,
        tstamp int(11) DEFAULT '0' NOT NULL,
        crdate int(11) DEFAULT '0' NOT NULL,
        cruser_id int(11) DEFAULT '0' NOT NULL,
        editlock tinyint(4) DEFAULT '0' NOT NULL,
        sys_language_uid int(11) DEFAULT '0' NOT NULL,
        l10n_parent int(11) DEFAULT '0' NOT NULL,
        l10n_diffsource mediumtext,

		name varchar(255),
        sheet_label mediumtext,
		source_table varchar(255),
		source_field varchar(255),
        source_uid int(11) DEFAULT '0' NOT NULL,
        form_fields int(11) DEFAULT '0' NOT NULL,
        json_data text,

        PRIMARY KEY (uid),
        KEY parent (pid)
);

#
# Table structure for table 'tx_fluxcapacitor_domain_model_field'
#
CREATE TABLE tx_fluxcapacitor_domain_model_field (
        uid int(11) NOT NULL auto_increment,
        pid int(11) DEFAULT '0' NOT NULL,
        tstamp int(11) DEFAULT '0' NOT NULL,
        crdate int(11) DEFAULT '0' NOT NULL,
        cruser_id int(11) DEFAULT '0' NOT NULL,
        editlock tinyint(4) DEFAULT '0' NOT NULL,
        sys_language_uid int(11) DEFAULT '0' NOT NULL,
        l10n_parent int(11) DEFAULT '0' NOT NULL,
        l10n_diffsource mediumtext,

        parent_field int(11) DEFAULT '0' NOT NULL,
        sheet int(11) DEFAULT '0' NOT NULL,
        field_name varchar(255),
        field_label mediumtext,
        field_type varchar(32),
        field_value text,
        field_options text,

        PRIMARY KEY (uid),
        KEY parent (pid),
        KEY parent_field (parent_field),
        KEY sheet (sheet),
        KEY field_value (field_value(32))
);
