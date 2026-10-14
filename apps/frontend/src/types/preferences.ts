export interface IdentityPreference {
    appName: string
    agencyName: string
    abbreviation: string
    logoFile?: File | string | null
    faviconFile?: File | string | null
}

export interface ColorPreference {
    primaryColor: string
    secondaryColor: string
    accentColor: string
}

export interface BannerPreference {
    bannerImage?: File | string | null
    title: string
    description: string
    ctaText: string
    showOnHomepage: boolean
}

export interface FooterPreference {
    address: string
    contactPhone: string
    contactEmail: string
    instagramUrl: string
    youtubeUrl: string
    copyrightText: string
}

export interface MetadataPreference {
    title: string
    description: string
    keywords: string
    canonicalUrl: string
    openGraphImage?: File | string | null
    allowIndex: boolean
    allowFollow: boolean
}