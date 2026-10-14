import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Fields, Text, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const p = { attributes, setAttributes };
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{
						key: 'email',
						label: __( 'E-postadress', 'studentspex' ),
						type: 'email',
					},
				] }
			/>
			<div { ...useBlockProps( { className: 'person' } ) }>
				<Text
					{ ...p }
					attr="role"
					tag="dt"
					plain
					placeholder={ __( 'Roll', 'studentspex' ) }
				/>
				<dd>
					<Text
						{ ...p }
						attr="name"
						tag="span"
						plain
						placeholder={ __( 'Namn', 'studentspex' ) }
					/>
					{ attributes.email && (
						<>
							<br />
							<a href={ `mailto:${ attributes.email }` }>
								{ attributes.email }
							</a>
						</>
					) }
				</dd>
			</div>
		</>
	);
}

register( metadata, Edit );
