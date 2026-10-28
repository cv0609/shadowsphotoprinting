{{-- Schema.org JSON-LD Structured Data --}}
@if(request()->is('/'))
{{-- Homepage Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "LocalBusiness",
      "@id": "https://shadowsphotoprinting.com.au/#localbusiness",
      "name": "Shadows Affordable Memories",
      "alternateName": "Shadows Photo Printing",
      "description": "Family-owned professional photo lab in Glenreagh NSW printing photo prints, scrapbook pages, canvas prints and enlargements in-house, delivered Australia-wide.",
      "url": "https://shadowsphotoprinting.com.au/",
      "telephone": "+61 2 6602 9424",
      "email": "shadowsphotoprinting@outlook.com",
      "priceRange": "$$",
      "image": "https://shadowsphotoprinting.com.au/assets/images/logo.png",
      "logo": "https://shadowsphotoprinting.com.au/assets/images/logo.png",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "21 Boundary Street",
        "addressLocality": "Glenreagh",
        "addressRegion": "NSW",
        "postalCode": "2450",
        "addressCountry": "AU"
      },
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
          "opens": "09:00",
          "closes": "16:30"
        }
      ],
      "areaServed": [
        { "@type": "Country", "name": "Australia" },
        { "@type": "Country", "name": "New Zealand" }
      ],
      "sameAs": [
        "https://www.facebook.com/PhotoPrintingShadowsCommunityHub",
        "https://www.instagram.com/shadowsphotoprinting"
      ],
      "parentOrganization": { "@id": "https://shadowsphotoprinting.com.au/#organization" }
    },
    {
      "@type": "Organization",
      "@id": "https://shadowsphotoprinting.com.au/#organization",
      "name": "Shadows Affordable Memories",
      "alternateName": "Shadows Photo Printing",
      "url": "https://shadowsphotoprinting.com.au/",
      "logo": "https://shadowsphotoprinting.com.au/assets/images/logo.png",
      "sameAs": [
        "https://www.facebook.com/PhotoPrintingShadowsCommunityHub",
        "https://www.instagram.com/shadowsphotoprinting"
      ]
    },
    {
      "@type": "WebSite",
      "@id": "https://shadowsphotoprinting.com.au/#website",
      "url": "https://shadowsphotoprinting.com.au/",
      "name": "Shadows Photo Printing",
      "publisher": { "@id": "https://shadowsphotoprinting.com.au/#organization" }
    },
    {
      "@type": "WebPage",
      "@id": "https://shadowsphotoprinting.com.au/#webpage",
      "url": "https://shadowsphotoprinting.com.au/",
      "name": "Shadows Photo Printing | Professional Photo Printing Australia",
      "isPartOf": { "@id": "https://shadowsphotoprinting.com.au/#website" },
      "about": { "@id": "https://shadowsphotoprinting.com.au/#localbusiness" },
      "primaryImageOfPage": {
        "@type": "ImageObject",
        "url": "https://shadowsphotoprinting.com.au/assets/images/logo.png"
      }
    }
  ]
}
</script>
@elseif(request()->is('our-products/photo-prints*'))
{{-- 1. Photo Prints Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Product",
      "@id": "https://shadowsphotoprinting.com.au/our-products/photo-prints#product",
      "name": "Photo Prints",
      "description": "Professional lustre photo prints from 5x5 to 12x48 inches, printed in-house in Glenreagh NSW with no automatic edits.",
      "image": "https://shadowsphotoprinting.com.au/assets/admin/uploads/printsEnlargement/1416242259-prints-and-enlargements.jpg",
      "url": "https://shadowsphotoprinting.com.au/our-products/photo-prints",
      "brand": { "@type": "Brand", "name": "Shadows Photo Printing" },
      "offers": {
        "@type": "AggregateOffer",
        "priceCurrency": "AUD",
        "lowPrice": "0.55",
        "highPrice": "11.00",
        "availability": "https://schema.org/InStock",
        "url": "https://shadowsphotoprinting.com.au/our-products/photo-prints",
        "seller": { "@id": "https://shadowsphotoprinting.com.au/#organization" }
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://shadowsphotoprinting.com.au/" },
        { "@type": "ListItem", "position": 2, "name": "Our Products", "item": "https://shadowsphotoprinting.com.au/our-products" },
        { "@type": "ListItem", "position": 3, "name": "Photo Prints", "item": "https://shadowsphotoprinting.com.au/our-products/photo-prints" }
      ]
    }
  ]
}
</script>
@elseif(request()->is('our-products/scrapbook-page-printing*') || request()->is('our-products/scrapbook-prints*'))
{{-- 2. Scrapbook Page Printing Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Product",
      "@id": "https://shadowsphotoprinting.com.au/our-products/scrapbook-page-printing#product",
      "name": "Scrapbook Page Printing",
      "description": "6x8, 8x8, 10x10 and 12x12 scrapbook pages printed on professional lustre paper, exactly as supplied.",
      "image": "https://shadowsphotoprinting.com.au/assets/admin/uploads/scrapbook/53827508-scrapbook-prints.jpg",
      "url": "https://shadowsphotoprinting.com.au/our-products/scrapbook-page-printing",
      "brand": { "@type": "Brand", "name": "Shadows Photo Printing" },
      "offers": {
        "@type": "AggregateOffer",
        "priceCurrency": "AUD",
        "lowPrice": "0.95",
        "highPrice": "1.95",
        "availability": "https://schema.org/InStock",
        "url": "https://shadowsphotoprinting.com.au/our-products/scrapbook-page-printing",
        "seller": { "@id": "https://shadowsphotoprinting.com.au/#organization" }
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://shadowsphotoprinting.com.au/" },
        { "@type": "ListItem", "position": 2, "name": "Our Products", "item": "https://shadowsphotoprinting.com.au/our-products" },
        { "@type": "ListItem", "position": 3, "name": "Scrapbook Page Printing", "item": "https://shadowsphotoprinting.com.au/our-products/scrapbook-page-printing" }
      ]
    }
  ]
}
</script>
@elseif(request()->is('our-products/canvas-prints*'))
{{-- 3. Canvas Prints Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Product",
      "@id": "https://shadowsphotoprinting.com.au/our-products/canvas-prints#product",
      "name": "Canvas Prints",
      "description": "Handmade gallery-wrapped canvas prints on 38mm stretcher bars, printed and stretched in-house in Glenreagh NSW.",
      "image": "https://shadowsphotoprinting.com.au/assets/admin/uploads/canvas/714534716-CANVAS-PRINTS.jpg",
      "url": "https://shadowsphotoprinting.com.au/our-products/canvas-prints",
      "brand": { "@type": "Brand", "name": "Shadows Photo Printing" },
      "offers": {
        "@type": "AggregateOffer",
        "priceCurrency": "AUD",
        "lowPrice": "45.95",
        "highPrice": "549.65",
        "availability": "https://schema.org/InStock",
        "url": "https://shadowsphotoprinting.com.au/our-products/canvas-prints",
        "seller": { "@id": "https://shadowsphotoprinting.com.au/#organization" }
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://shadowsphotoprinting.com.au/" },
        { "@type": "ListItem", "position": 2, "name": "Our Products", "item": "https://shadowsphotoprinting.com.au/our-products" },
        { "@type": "ListItem", "position": 3, "name": "Canvas Prints", "item": "https://shadowsphotoprinting.com.au/our-products/canvas-prints" }
      ]
    }
  ]
}
</script>
@elseif(request()->is('our-products/photo-enlargements*') || request()->is('our-products/prints-enlargements*'))
{{-- 4. Photo Enlargements Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Product",
      "@id": "https://shadowsphotoprinting.com.au/our-products/photo-enlargements#product",
      "name": "Photo Enlargements",
      "description": "Large photo enlargements from 16x16 to 40x60 inches on premium satin photo paper, printed in-house in Glenreagh NSW.",
      "image": "https://shadowsphotoprinting.com.au/assets/admin/uploads/posterspanoramics/1453810877-checkout-page.jpg",
      "url": "https://shadowsphotoprinting.com.au/our-products/photo-enlargements",
      "brand": { "@type": "Brand", "name": "Shadows Photo Printing" },
      "offers": {
        "@type": "AggregateOffer",
        "priceCurrency": "AUD",
        "lowPrice": "16.85",
        "highPrice": "155.95",
        "availability": "https://schema.org/InStock",
        "url": "https://shadowsphotoprinting.com.au/our-products/photo-enlargements",
        "seller": { "@id": "https://shadowsphotoprinting.com.au/#organization" }
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://shadowsphotoprinting.com.au/" },
        { "@type": "ListItem", "position": 2, "name": "Our Products", "item": "https://shadowsphotoprinting.com.au/our-products" },
        { "@type": "ListItem", "position": 3, "name": "Photo Enlargements", "item": "https://shadowsphotoprinting.com.au/our-products/photo-enlargements" }
      ]
    }
  ]
}
</script>
@elseif(request()->is('our-products/poster-prints*'))
{{-- 5. Poster Prints Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Product",
      "@id": "https://shadowsphotoprinting.com.au/our-products/poster-prints#product",
      "name": "Poster Prints",
      "description": "Poster printing from A4 to B0 on PhotoSatin Premium paper, printed in-house in Glenreagh NSW.",
      "url": "https://shadowsphotoprinting.com.au/our-products/poster-prints",
      "brand": { "@type": "Brand", "name": "Shadows Photo Printing" },
      "offers": {
        "@type": "AggregateOffer",
        "priceCurrency": "AUD",
        "lowPrice": "1.85",
        "highPrice": "80.65",
        "availability": "https://schema.org/InStock",
        "url": "https://shadowsphotoprinting.com.au/our-products/poster-prints",
        "seller": { "@id": "https://shadowsphotoprinting.com.au/#organization" }
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://shadowsphotoprinting.com.au/" },
        { "@type": "ListItem", "position": 2, "name": "Our Products", "item": "https://shadowsphotoprinting.com.au/our-products" },
        { "@type": "ListItem", "position": 3, "name": "Poster Prints", "item": "https://shadowsphotoprinting.com.au/our-products/poster-prints" }
      ]
    }
  ]
}
</script>
@elseif(request()->is('our-products/print-bundles-for-every-occasion*') || request()->is('our-products/wedding-package*'))
{{-- 6. Print Bundles Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Product",
      "@id": "https://shadowsphotoprinting.com.au/our-products/print-bundles-for-every-occasion#product",
      "name": "Print Bundles for Every Occasion",
      "description": "Photo print and canvas bundles for weddings and special occasions, from 20 prints and one canvas up to 100 prints and five large canvases.",
      "url": "https://shadowsphotoprinting.com.au/our-products/print-bundles-for-every-occasion",
      "brand": { "@type": "Brand", "name": "Shadows Photo Printing" },
      "offers": {
        "@type": "AggregateOffer",
        "priceCurrency": "AUD",
        "lowPrice": "86.40",
        "highPrice": "840.50",
        "availability": "https://schema.org/InStock",
        "url": "https://shadowsphotoprinting.com.au/our-products/print-bundles-for-every-occasion",
        "seller": { "@id": "https://shadowsphotoprinting.com.au/#organization" }
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://shadowsphotoprinting.com.au/" },
        { "@type": "ListItem", "position": 2, "name": "Our Products", "item": "https://shadowsphotoprinting.com.au/our-products" },
        { "@type": "ListItem", "position": 3, "name": "Print Bundles for Every Occasion", "item": "https://shadowsphotoprinting.com.au/our-products/print-bundles-for-every-occasion" }
      ]
    }
  ]
}
</script>
@elseif(request()->is('our-products/photos-for-sale*'))
{{-- 7. Photos for Sale Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://shadowsphotoprinting.com.au/" },
    { "@type": "ListItem", "position": 2, "name": "Our Products", "item": "https://shadowsphotoprinting.com.au/our-products" },
    { "@type": "ListItem", "position": 3, "name": "Photos for Sale", "item": "https://shadowsphotoprinting.com.au/our-products/photos-for-sale" }
  ]
}
</script>
@elseif(request()->is('our-products/gift-card*'))
{{-- 8. Gift Card Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://shadowsphotoprinting.com.au/" },
    { "@type": "ListItem", "position": 2, "name": "Our Products", "item": "https://shadowsphotoprinting.com.au/our-products" },
    { "@type": "ListItem", "position": 3, "name": "Gift Cards", "item": "https://shadowsphotoprinting.com.au/our-products/gift-card" }
  ]
}
</script>
@elseif(request()->is('our-products/hand-craft*'))
{{-- 9. Hand-craft Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://shadowsphotoprinting.com.au/" },
    { "@type": "ListItem", "position": 2, "name": "Our Products", "item": "https://shadowsphotoprinting.com.au/our-products" },
    { "@type": "ListItem", "position": 3, "name": "Handcrafted Gifts", "item": "https://shadowsphotoprinting.com.au/our-products/hand-craft" }
  ]
}
</script>
@endif

