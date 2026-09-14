import { __ } from '@wordpress/i18n';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { TextControl, TextareaControl, ToggleControl, Button, RadioControl } from '@wordpress/components';

/**
 * Generic repeater UI for a block attribute holding an array of row objects.
 * The block-editor equivalent of the `repeater` field type in
 * inc/options.php — same sub-field vocabulary (text/textarea/image), separate
 * implementation since one runs in wp-admin and the other inside the block
 * editor's React tree.
 *
 * Rows lay out inline by default (`layout: 'row'`): each sub-field takes an
 * equal-width slot, with compact move-up/move-down/remove icon buttons at
 * the row's end. No shared column-header row — a field like `link` renders
 * two stacked inputs where a `text` field renders one, so a single header
 * cell can't line up with both consistently. Instead each control shows
 * its label as `placeholder` text (and keeps a screen-reader-only real
 * `label` via `hideLabelFromVision`), which self-documents regardless of
 * how many inputs a field actually renders.
 *
 * `layout: 'column'` stacks each row's sub-fields vertically instead —
 * there's no shared column header in that layout (it wouldn't line up with
 * anything), so each sub-field's own label renders visibly above its
 * control instead of being screen-reader-only.
 *
 * @param {Object}   props
 * @param {string}   props.label    Field group label.
 * @param {Object[]} props.value    Current rows.
 * @param {Function} props.onChange ( rows ) => void
 * @param {Object[]} props.fields   [ { name, label, type: 'text'|'number'|'textarea'|'image'|'file'|'link'|'radio', help, mimeTypes, linkTarget, options } ]
 *                                  `linkTarget` (link fields only) adds an "open in new tab" toggle,
 *                                  storing `{name}Target` on the row — same opt-in shape as the
 *                                  top-level `link` field type's `link_target` option. `options`
 *                                  (radio fields only) is `[ { label, value } ]`, mirroring the
 *                                  top-level `select`/`radio` field types' options shape.
 * @param {Object}   props.emptyRow Shape of a freshly-added row, e.g. { stat: '', title: '' }.
 * @param {string}   [props.layout] 'row' (default) or 'column'.
 */
export default function RepeaterField( { label, value, onChange, fields, emptyRow, layout = 'row' } ) {
	const isColumn = 'column' === layout;
	const rows = value || [];

	function updateRow( index, patch ) {
		const next = rows.slice();
		next[ index ] = { ...next[ index ], ...patch };
		onChange( next );
	}

	function addRow() {
		onChange( [ ...rows, { ...emptyRow } ] );
	}

	function removeRow( index ) {
		// eslint-disable-next-line no-alert -- a plain confirm() is enough
		// friction for an irreversible remove; no undo exists for this field.
		if ( ! window.confirm( __( 'Remove this row?', 'lc-tidyjs2026' ) ) ) {
			return;
		}
		onChange( rows.filter( ( _row, i ) => i !== index ) );
	}

	function moveRow( index, direction ) {
		const target = index + direction;
		if ( target < 0 || target >= rows.length ) {
			return;
		}
		const next = rows.slice();
		const tmp = next[ index ];
		next[ index ] = next[ target ];
		next[ target ] = tmp;
		onChange( next );
	}

	return (
		<div
			className={
				isColumn
					? 'lc-tidyjs2026-repeater-field lc-tidyjs2026-repeater-field--column'
					: 'lc-tidyjs2026-repeater-field'
			}
		>
			<label className="lc-tidyjs2026-editor-field__label">{ label }</label>
			<div className="lc-tidyjs2026-repeater-field__rows">
			{ rows.map( ( row, index ) => (
				<div className="lc-tidyjs2026-repeater-field__row" key={ index }>
					<span className="lc-tidyjs2026-repeater-field__number">{ index + 1 }</span>
					{ fields.map( ( field ) => {
						if ( 'image' === field.type ) {
							return (
								<div key={ field.name }>
									{ isColumn && (
										<label className="lc-tidyjs2026-editor-field__label">{ field.label }</label>
									) }
									<MediaUploadCheck>
									<MediaUpload
										onSelect={ ( media ) =>
											updateRow( index, {
												[ field.name ]: media.id,
												[ `${ field.name }Url` ]: media.url,
											} )
										}
										allowedTypes={ [ 'image' ] }
										value={ row[ field.name ] }
										render={ ( { open } ) => (
											<div className="lc-tidyjs2026-repeater-field__image">
												{ row[ `${ field.name }Url` ] && (
													<img src={ row[ `${ field.name }Url` ] } alt="" />
												) }
												<Button variant="secondary" size="small" onClick={ open }>
													{ row[ field.name ]
														? __( 'Replace', 'lc-tidyjs2026' )
														: __( 'Select', 'lc-tidyjs2026' ) }
												</Button>
											</div>
										) }
									/>
									</MediaUploadCheck>
								</div>
							);
						}

						if ( 'link' === field.type ) {
							return (
								<div className="lc-tidyjs2026-repeater-field__link" key={ field.name }>
									<TextControl
										label={ __( `${ field.label } Title`, 'lc-tidyjs2026' ) }
										hideLabelFromVision={ ! isColumn }
										placeholder={ isColumn ? undefined : __( `${ field.label } Title`, 'lc-tidyjs2026' ) }
										value={ row[ `${ field.name }Text` ] || '' }
										onChange={ ( v ) => updateRow( index, { [ `${ field.name }Text` ]: v } ) }
									/>
									<TextControl
										type="url"
										label={ __( `${ field.label } URL`, 'lc-tidyjs2026' ) }
										hideLabelFromVision={ ! isColumn }
										placeholder={ isColumn ? undefined : __( `${ field.label } URL`, 'lc-tidyjs2026' ) }
										value={ row[ field.name ] || '' }
										onChange={ ( v ) => updateRow( index, { [ field.name ]: v } ) }
										help={ field.help }
									/>
									{ field.linkTarget && (
										<ToggleControl
											label={ __( `Open ${ field.label } in a new tab`, 'lc-tidyjs2026' ) }
											checked={ !! row[ `${ field.name }Target` ] }
											onChange={ ( v ) => updateRow( index, { [ `${ field.name }Target` ]: v } ) }
										/>
									) }
								</div>
							);
						}

						if ( 'file' === field.type ) {
							return (
								<div key={ field.name }>
									{ isColumn && (
										<label className="lc-tidyjs2026-editor-field__label">{ field.label }</label>
									) }
									<MediaUploadCheck>
									<MediaUpload
										onSelect={ ( media ) =>
											updateRow( index, {
												[ field.name ]: media.id,
												[ `${ field.name }Name` ]: media.filename || media.title || '',
											} )
										}
										allowedTypes={ field.mimeTypes || [] }
										value={ row[ field.name ] }
										render={ ( { open } ) => (
											<div className="lc-tidyjs2026-repeater-field__image">
												{ row[ `${ field.name }Name` ] && (
													<span className="lc-tidyjs2026-repeater-field__file-name">
														{ row[ `${ field.name }Name` ] }
													</span>
												) }
												<Button variant="secondary" size="small" onClick={ open }>
													{ row[ field.name ]
														? __( 'Replace', 'lc-tidyjs2026' )
														: __( 'Select', 'lc-tidyjs2026' ) }
												</Button>
											</div>
										) }
									/>
									</MediaUploadCheck>
								</div>
							);
						}

						if ( 'textarea' === field.type ) {
							return (
								<TextareaControl
									key={ field.name }
									label={ field.label }
									hideLabelFromVision={ ! isColumn }
									placeholder={ isColumn ? undefined : field.label }
									value={ row[ field.name ] || '' }
									onChange={ ( v ) => updateRow( index, { [ field.name ]: v } ) }
									help={ field.help }
								/>
							);
						}

						if ( 'radio' === field.type ) {
							return (
								<RadioControl
									key={ field.name }
									label={ field.label }
									hideLabelFromVision={ ! isColumn }
									selected={ row[ field.name ] || '' }
									options={ field.options || [] }
									onChange={ ( v ) => updateRow( index, { [ field.name ]: v } ) }
								/>
							);
						}

						if ( 'number' === field.type ) {
							return (
								<TextControl
									key={ field.name }
									type="number"
									label={ field.label }
									hideLabelFromVision={ ! isColumn }
									placeholder={ isColumn ? undefined : field.label }
									value={ row[ field.name ] ?? '' }
									onChange={ ( v ) => updateRow( index, { [ field.name ]: '' === v ? '' : Number( v ) } ) }
									help={ field.help }
								/>
							);
						}

						return (
							<TextControl
								key={ field.name }
								label={ field.label }
								hideLabelFromVision={ ! isColumn }
								placeholder={ isColumn ? undefined : field.label }
								value={ row[ field.name ] || '' }
								onChange={ ( v ) => updateRow( index, { [ field.name ]: v } ) }
								help={ field.help }
							/>
						);
					} ) }
					<div className="lc-tidyjs2026-repeater-field__row-actions">
						<Button
							size="small"
							label={ __( 'Move up', 'lc-tidyjs2026' ) }
							onClick={ () => moveRow( index, -1 ) }
							disabled={ 0 === index }
						>
							&#9650;
						</Button>
						<Button
							size="small"
							label={ __( 'Move down', 'lc-tidyjs2026' ) }
							onClick={ () => moveRow( index, 1 ) }
							disabled={ index === rows.length - 1 }
						>
							&#9660;
						</Button>
						<Button
							size="small"
							isDestructive
							label={ __( 'Remove', 'lc-tidyjs2026' ) }
							onClick={ () => removeRow( index ) }
						>
							&times;
						</Button>
					</div>
				</div>
			) ) }
			</div>
			<Button variant="primary" onClick={ addRow }>
				{ __( 'Add row', 'lc-tidyjs2026' ) }
			</Button>
		</div>
	);
}
