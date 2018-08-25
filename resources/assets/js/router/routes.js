// Initialize
const Dashboard = () => import('~/pages/Dashboard').then(m => m.default || m)
const Medias = () => import('~/pages/common/Medias').then(m => m.default || m)
const Brands = () => import('~/pages/creative/Brands').then(m => m.default || m)
const BrandDetails = () => import('~/pages/creative/parts/BrandDetails').then(m => m.default || m)
const ShowMedia = () => import('~/pages/common/ShowMediaComponent').then(m => m.default || m)
const EditMedia = () => import('~/pages/common/EditMediaComponent').then(m => m.default || m)
const EditLicense = () => import('~/pages/common/EditLicenseComponent').then(m => m.default || m)
const Uploaded = () => import('~/pages/common/Uploaded').then(m => m.default || m)
const Welcome = () => import('~/pages/welcome').then(m => m.default || m)

const Login = () => import('~/pages/auth/login').then(m => m.default || m)
const Register = () => import('~/pages/auth/register').then(m => m.default || m)
const RegisterCreative = () => import('~/pages/auth/registerCreative').then(m => m.default || m)
const RegisterBrand = () => import('~/pages/auth/registerBrand').then(m => m.default || m)
const PasswordReset = () => import('~/pages/auth/password/reset').then(m => m.default || m)
const PasswordRequest = () => import('~/pages/auth/password/email').then(m => m.default || m)
const EmailConfirmation = () => import('~/pages/auth/password/emailConfirmation').then(m => m.default || m)

const Payment = () => import('~/pages/payment/payment').then(m => m.default || m)
const PaymentDetails = () => import('~/pages/payment/PaymentDetails').then(m => m.default || m)
const SelectPlan = () => import('~/pages/brand/ChoicePlanComponent').then(m => m.default || m)

const Settings = () => import('~/pages/settings/index').then(m => m.default || m)
const SettingsProfile = () => import('~/pages/settings/profile').then(m => m.default || m)
const SettingsPassword = () => import('~/pages/settings/password').then(m => m.default || m)
const SettingsPayment = () => import('~/pages/settings/payments').then(m => m.default || m)
const SettingsStorage = () => import('~/pages/settings/storage').then(m => m.default || m)
const SettingsSubscription = () => import('~/pages/settings/subscription-invoices').then(m => m.default || m)

const LicensesComponent = () => import('~/pages/common/LicensesComponent').then(m => m.default || m)
const ManageCreatives = () => import('~/pages/common/ManageCreatives').then(m => m.default || m)

// Export
export default [
    {path: '/', name: 'welcome', component: Welcome},

    {path: '/login', name: 'login', component: Login},
    {path: '/register', name: 'register', component: Register},
    {path: '/register/creative', name: 'register.creative', component: RegisterCreative},
    {path: '/register/brand', name: 'register.brand', component: RegisterBrand},
    {path: '/password/reset', name: 'password.request', component: PasswordRequest},
    {path: '/password/reset/:token', name: 'password.reset', component: PasswordReset},
    {path: '/email/confirm/:confirmationToken', name: 'email.confirm', component: EmailConfirmation},

    {path: '/dashboard', name: 'dashboard', component: Dashboard},
    {path: '/brand/details/:id', name: 'creative.brand.details', component: Dashboard},
    {path: '/medias', name: 'medias', component: Medias},
    {path: '/medias/:creative_brand_id', name: 'creative.brand.medias', component: Medias, props: true},
    {path: '/medias/:id/show', name: 'medias.show', component: ShowMedia},
    {path: '/medias/:id/edit', name: 'medias.edit', component: EditMedia},
    {path: '/uploaded', name: 'uploaded', component: Uploaded},
    {path: '/payment/:id', name: 'payment', component: Payment},
    {path: '/payment-details', name: 'payment.details', component: PaymentDetails},
    {path: '/select-plan', name: 'select-plan', component: SelectPlan},
    {path: '/licenses', name: 'licenses', component: LicensesComponent},
    {path: '/licenses/:id/edit', name: 'licenses.edit', component: EditLicense},
    {path: '/manage-creatives', name: 'brand.creatives', component: ManageCreatives},

    {path: '/licenses', name: 'creative.licenses', component: LicensesComponent},
    {path: '/brands', name: 'creative.brands', component: Brands},
    {path: '/creative/brand/details', name: 'brand.details.show', component: BrandDetails},
    {path: '/creative/brand/remove/:id', name: 'brand.creative.edit', component: BrandDetails},
    {path: '/creative/brand/edit/:id', name: 'brand.creative.remove', component: BrandDetails},

    {
        path: '/settings', component: Settings, children: [
            {path: '', redirect: {name: 'settings.profile'}},
            {path: 'profile', name: 'settings.profile', component: SettingsProfile},
            {path: 'password', name: 'settings.password', component: SettingsPassword},
            {path: 'payments', name: 'settings.payments', component: SettingsPayment},
            {path: 'storage', name: 'settings.storage-ftp', component: SettingsStorage},
            {path: 'subscription-invoices', name: 'settings.subscription-invoices', component: SettingsSubscription}
        ]
    },

    {path: '*', component: require('~/pages/errors/404.vue')}
]
