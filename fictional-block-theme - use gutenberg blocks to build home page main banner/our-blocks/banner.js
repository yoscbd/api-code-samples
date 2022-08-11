import apiFetch from "@wordpress/api-fetch"  //will allow us to pull images data via wp media rest
import { Button, PanelBody, PanelRow } from "@wordpress/components"
import { InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor"
import { registerBlockType } from "@wordpress/blocks"
import { useEffect } from "@wordpress/element"  //will aloow us to "listen" to a change on a prop and fire a fonction on change
registerBlockType("ourblocktheme/banner", {
  title: "Banner",
  supports: {
    align: ["full"] // full width on editor screen for this block only
  },
  attributes: {
    align: { type: "string", default: "full" },
    imgID: { type: "number" },
    imgURL: { type: "string", default: banner.fallbackimage }
  },
  edit: EditComponent,
  save: SaveComponent
})

function EditComponent(props) {
  useEffect(
    function () { //Fire this function every time "imgID" prop is changed (see line 34)
      if (props.attributes.imgID) { // first check if the user has selected an image
        async function go() {
          const response = await apiFetch({ //fetch relevant image data with "apiFetch"
            path: `/wp/v2/media/${props.attributes.imgID}`, // the root for the media REST + current image id
            method: "GET"
          })
          props.setAttributes({ imgURL: response.media_details.sizes.pageBanner.source_url }) //we update the img url property with the new uploaded image
        }
        go() //fire this async funtion
      }
    },
    [props.attributes.imgID] // this is the property we listen to with "useEffect" (see line 21)
  )

  function onFileSelect(x) {
    props.setAttributes({ imgID: x.id })
  }

  return (
    <>
      <InspectorControls>
        <PanelBody title="Background" initialOpen={true}>
          <PanelRow>
            <MediaUploadCheck>   {/* This is a wordpress component that will add security such as chcking first that the user is logged in etc... */}
              {/*  "MediaUpload" is The image uploader in the editor */}
              <MediaUpload
                onSelect={onFileSelect}
                value={props.attributes.imgID}
                render={({ open }) => {
                  {/*  Adding an image upload button to he panel */ }
                  return <Button onClick={open}>Choose Image</Button>
                }}
              />
            </MediaUploadCheck>
          </PanelRow>
        </PanelBody>
      </InspectorControls>

      <div className="page-banner">
        <div className="page-banner__bg-image" style={{ backgroundImage: `url('${props.attributes.imgURL}')` }}></div>
        <div className="page-banner__content container t-center c-white">
          {/*  add support and restriction so only these bloxks can be added inside the banner parent block: */}
          <InnerBlocks allowedBlocks={["ourblocktheme/genericheading", "ourblocktheme/genericbutton"]} />
        </div>
      </div>
    </>
  )
}

function SaveComponent() {
  return <InnerBlocks.Content />
  {/* 
  The content for the banner section will be rendered using "render_callback" in function.php
  so we only return the content of the block = all nested block inside our block  
  */ }
}
