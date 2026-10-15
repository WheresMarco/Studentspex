import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Fields, Text, register } from '../../shared/editor';
import metadata from './block.json';

function FakeForm( { p, dark } ) {
	return (
		<div className={ `form${ dark ? ' form--dark' : '' }` }>
			<div className="form__row">
				<div className="field">
					<span className="field__label">
						{ __( 'E-postadress', 'studentspex' ) }
					</span>
					<input
						className="input"
						type="email"
						placeholder="namn@exempel.se"
						disabled
					/>
				</div>
				<div style={ { alignSelf: 'flex-end' } }>
					<span className={ `btn${ dark ? '' : ' btn--blue' }` }>
						<Text { ...p } attr="buttonText" tag="span" plain />
					</span>
				</div>
			</div>
			<div className="check">
				<input type="checkbox" disabled />
				<Text { ...p } attr="consent" tag="span" plain />
			</div>
		</div>
	);
}

function Edit( { attributes, setAttributes } ) {
	const p = { attributes, setAttributes };
	const footer = attributes.variant === 'footer';
	const blockProps = useBlockProps( {
		className: footer ? 'footer-news' : 'newsletter',
	} );
	const settings = (
		<Fields
			attributes={ attributes }
			setAttributes={ setAttributes }
			fields={ [
				{
					key: 'variant',
					label: __( 'Utseende', 'studentspex' ),
					type: 'select',
					options: [
						{
							label: __( 'Kort (ljust)', 'studentspex' ),
							value: 'card',
						},
						{
							label: __( 'Sidfot (mörkt)', 'studentspex' ),
							value: 'footer',
						},
					],
				},
			] }
		/>
	);
	if ( footer ) {
		return (
			<>
				{ settings }
				<div { ...blockProps }>
					<Text { ...p } attr="title" tag="h2" plain />
					<Text { ...p } attr="lead" />
					<FakeForm p={ p } dark />
				</div>
			</>
		);
	}
	return (
		<>
			{ settings }
			<div { ...blockProps }>
				<div className="newsletter__head">
					<Text
						{ ...p }
						attr="eyebrow"
						className="eyebrow"
						plain
						placeholder={ __( 'Överrubrik', 'studentspex' ) }
					/>
					<Text { ...p } attr="title" tag="h2" plain />
					<Text { ...p } attr="lead" className="lead" />
				</div>
				<div className="newsletter__form">
					<FakeForm p={ p } />
				</div>
				<p className="newsletter__promise">
					<span aria-hidden="true">✦</span>
					<Text
						{ ...p }
						attr="promise"
						tag="span"
						plain
						placeholder={ __( 'Löfte (valfritt)', 'studentspex' ) }
					/>
				</p>
			</div>
		</>
	);
}

register( metadata, Edit );
