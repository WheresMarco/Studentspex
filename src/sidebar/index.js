/**
 * Editor sidebar for "Uppsättning": everything except title, plot (post content)
 * and poster (featured image), which use the normal editor UI.
 */
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { useSelect } from '@wordpress/data';
import {
	TextControl,
	Button,
	Flex,
	FlexBlock,
	FlexItem,
	Notice,
} from '@wordpress/components';
import { ImageField, editorData } from '../shared/editor';
import { useEntityProp, store as coreStore } from '@wordpress/core-data';

function MetaImage( { label, metaKey, meta, setMeta, help } ) {
	const id = meta[ metaKey ] || 0;
	const url = useSelect(
		( select ) => {
			if ( ! id ) {
				return '';
			}
			const media = select( coreStore ).getMedia( id );
			return media ? media.source_url : '';
		},
		[ id ]
	);
	return (
		<>
			<ImageField
				label={ label }
				id={ id }
				url={ url }
				onChange={ ( media ) =>
					setMeta( { ...meta, [ metaKey ]: media ? media.id : 0 } )
				}
			/>
			{ help && (
				<p
					className="components-base-control__help"
					style={ { marginTop: -8 } }
				>
					{ help }
				</p>
			) }
		</>
	);
}

function Performances( { meta, setMeta } ) {
	const rows = Array.isArray( meta.performances ) ? meta.performances : [];
	const update = ( i, key, value ) => {
		const next = rows.map( ( r, j ) =>
			j === i ? { ...r, [ key ]: value } : r
		);
		setMeta( { ...meta, performances: next } );
	};
	const remove = ( i ) =>
		setMeta( {
			...meta,
			performances: rows.filter( ( _, j ) => j !== i ),
		} );
	const add = () =>
		setMeta( {
			...meta,
			performances: [ ...rows, { date: '', time: '19.00', label: '' } ],
		} );
	return (
		<div className="spex-perfs">
			{ rows.map( ( row, i ) => (
				<div
					key={ i }
					style={ { borderTop: '1px solid #ddd', padding: '10px 0' } }
				>
					<Flex align="flex-end">
						<FlexBlock>
							<TextControl
								label={ __( 'Datum', 'studentspex' ) }
								type="date"
								value={ row.date }
								onChange={ ( v ) => update( i, 'date', v ) }
								__nextHasNoMarginBottom
								__next40pxDefaultSize
							/>
						</FlexBlock>
						<FlexItem>
							<TextControl
								label={ __( 'Tid', 'studentspex' ) }
								value={ row.time }
								onChange={ ( v ) => update( i, 'time', v ) }
								style={ { width: 80 } }
								__nextHasNoMarginBottom
								__next40pxDefaultSize
							/>
						</FlexItem>
					</Flex>
					<Flex align="flex-end" style={ { marginTop: 8 } }>
						<FlexBlock>
							<TextControl
								label={ __(
									'Etikett (valfri)',
									'studentspex'
								) }
								placeholder={
									i === 0
										? __( 'Premiär', 'studentspex' )
										: ''
								}
								value={ row.label }
								onChange={ ( v ) => update( i, 'label', v ) }
								__nextHasNoMarginBottom
								__next40pxDefaultSize
							/>
						</FlexBlock>
						<FlexItem>
							<Button
								isDestructive
								variant="tertiary"
								onClick={ () => remove( i ) }
								label={ __(
									'Ta bort föreställning',
									'studentspex'
								) }
								icon="trash"
							/>
						</FlexItem>
					</Flex>
				</div>
			) ) }
			<Button variant="secondary" onClick={ add } icon="plus">
				{ __( 'Lägg till föreställning', 'studentspex' ) }
			</Button>
			<p className="components-base-control__help">
				{ __(
					'Sorteras efter datum. Den första markeras som premiär.',
					'studentspex'
				) }
			</p>
		</div>
	);
}

function Text( { label, k, meta, setMeta, help, type = 'text', placeholder } ) {
	return (
		<TextControl
			label={ label }
			help={ help }
			type={ type }
			placeholder={ placeholder }
			value={ meta[ k ] || '' }
			onChange={ ( v ) => setMeta( { ...meta, [ k ]: v } ) }
			__nextHasNoMarginBottom
			__next40pxDefaultSize
			style={ { marginBottom: 12 } }
		/>
	);
}

function Sidebar() {
	const { postType, postId } = useSelect( ( select ) => ( {
		postType: select( 'core/editor' ).getCurrentPostType(),
		postId: select( 'core/editor' ).getCurrentPostId(),
	} ) );
	const [ meta, setMeta ] = useEntityProp(
		'postType',
		'spex_uppsattning',
		'meta'
	);
	if ( postType !== 'spex_uppsattning' || ! meta ) {
		return null;
	}
	const data = editorData();
	const isCurrent = data.currentShow && data.currentShow.id === postId;
	const p = { meta, setMeta };

	return (
		<>
			<PluginDocumentSettingPanel
				name="spex-basics"
				title={ __( 'Uppsättning', 'studentspex' ) }
				initialOpen
			>
				{ isCurrent ? (
					<Notice status="info" isDismissible={ false }>
						{ __(
							'Det här är aktuell uppsättning (Nästa spex).',
							'studentspex'
						) }
					</Notice>
				) : (
					<p>
						<a href={ data.settingsUrl }>
							{ __(
								'Gör till aktuell uppsättning →',
								'studentspex'
							) }
						</a>
					</p>
				) }
				<TextControl
					label={ __( 'År', 'studentspex' ) }
					type="number"
					min={ 1900 }
					max={ 2200 }
					value={ meta.year || '' }
					onChange={ ( v ) =>
						setMeta( { ...meta, year: parseInt( v, 10 ) || 0 } )
					}
					__nextHasNoMarginBottom
					__next40pxDefaultSize
				/>
				<div style={ { height: 12 } } />
				<Text
					{ ...p }
					k="year_label"
					label={ __( 'Årtal som visas (valfritt)', 'studentspex' ) }
					placeholder="1984 & 1989"
					help={ __(
						'Om uppsättningen spelades flera år.',
						'studentspex'
					) }
				/>
				<Text
					{ ...p }
					k="subtitle"
					label={ __( 'Undertitel', 'studentspex' ) }
					placeholder="En avlidande manöver"
				/>
			</PluginDocumentSettingPanel>

			<PluginDocumentSettingPanel
				name="spex-images"
				title={ __( 'Bilder på startsidan', 'studentspex' ) }
			>
				<MetaImage
					{ ...p }
					metaKey="cover_image"
					label={ __( 'Omslagsbild', 'studentspex' ) }
					help={ __(
						'Bakgrund i startsidans topp när uppsättningen är aktuell. Liggande, minst 2000 px bred. Utan bild visas diamantmönstret.',
						'studentspex'
					) }
				/>
				<MetaImage
					{ ...p }
					metaKey="lisse_image"
					label={ __( 'Årets Lisse', 'studentspex' ) }
					help={ __(
						'Lisse i årets kostym, PNG med genomskinlig bakgrund. Ersätter standard-Lisse på startsidan.',
						'studentspex'
					) }
				/>
				<p className="components-base-control__help">
					{ __(
						'Affischen väljs under "Affisch" ovan.',
						'studentspex'
					) }
				</p>
			</PluginDocumentSettingPanel>

			<PluginDocumentSettingPanel
				name="spex-performances"
				title={ __( 'Föreställningar', 'studentspex' ) }
			>
				<Performances { ...p } />
				<div style={ { height: 16 } } />
				<Text
					{ ...p }
					k="venue"
					label={ __( 'Scen', 'studentspex' ) }
					placeholder="Forumteatern"
				/>
				<Text
					{ ...p }
					k="venue_address"
					label={ __( 'Adress', 'studentspex' ) }
					placeholder="Kårhuset Kollektivet, Ågatan 55, Linköping"
				/>
			</PluginDocumentSettingPanel>

			<PluginDocumentSettingPanel
				name="spex-tickets"
				title={ __( 'Biljetter', 'studentspex' ) }
			>
				<Text
					{ ...p }
					k="price_regular"
					label={ __( 'Ordinarie pris (kr)', 'studentspex' ) }
					placeholder="190"
				/>
				<Text
					{ ...p }
					k="price_reduced"
					label={ __( 'Rabatterat pris (kr)', 'studentspex' ) }
					placeholder="130"
				/>
				<Text
					{ ...p }
					k="price_reduced_note"
					label={ __( 'Vem får rabatt', 'studentspex' ) }
					placeholder="Studerande, pensionär, ung t.o.m. 17 år"
				/>
				<Text
					{ ...p }
					k="price_support"
					label={ __( 'Robin Hood-pris (kr)', 'studentspex' ) }
					placeholder="300"
					help={ __(
						'Ett högre pris för den som vill stödja spexet. Lämna tomt för att dölja det.',
						'studentspex'
					) }
				/>
				<Text
					{ ...p }
					k="ticket_url"
					type="url"
					label={ __( 'Länk till biljettköp', 'studentspex' ) }
					help={ __(
						'Om fältet är tomt döljs Biljetter-knapparna i menyn och på startsidan, och biljettrutan visar "Snart till salu".',
						'studentspex'
					) }
				/>
				<Text
					{ ...p }
					k="ticket_title"
					label={ __( 'Rubrik i biljettrutan', 'studentspex' ) }
					placeholder="Biljettsläpp i december"
				/>
				<Text
					{ ...p }
					k="ticket_text"
					label={ __( 'Text i biljettrutan', 'studentspex' ) }
					placeholder="Biljettkassan öppnar i december."
				/>
			</PluginDocumentSettingPanel>
		</>
	);
}

registerPlugin( 'studentspex-uppsattning', {
	render: Sidebar,
	icon: 'tickets-alt',
} );
