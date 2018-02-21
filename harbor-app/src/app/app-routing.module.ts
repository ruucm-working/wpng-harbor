import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AuthGuard } from './core/auth.guard';
import { UserLoginComponent } from './ui/user-login/user-login.component';
import { ItemsListComponent } from './items/items-list/items-list.component';
// import { ReadmePageComponent } from './ui/readme-page/readme-page.component';
import { HomePageComponent } from './ui/home-page/home-page.component';
import { NotesListComponent } from './notes/notes-list/notes-list.component';
import { CoreModule } from './core/core.module';
import { PostListComponent } from './posts/post-list/post-list.component';
import { PostSingleComponent } from './posts/post-single/post-single.component';
import { BuildSubmitComponent } from './programs/build-submit.component';
import { AdminComponent } from './admin/admin.component';
import { SubmitResultComponent } from './admin/submit-result/submit-result.component';


const routes: Routes = [
	{ path: '', redirectTo: 'app', pathMatch: 'full' },
	{ path: 'ng-admin', redirectTo: 'app/admin', pathMatch: 'full' },
	{ path: 'app', component: BuildSubmitComponent },
	{ path: 'app/login', component: UserLoginComponent, },
	{ path: 'app/items', component: ItemsListComponent, canActivate: [AuthGuard] },
	{ path: 'app/notes', component: NotesListComponent },
	// uploads are lazy loaded
	{ path: 'app/uploads', loadChildren: './uploads/shared/upload.module#UploadModule', canActivate: [AuthGuard] },
	{
		path: 'app/posts',
		component: PostListComponent,
		pathMatch: 'full'
	},
	{
		path: 'app/posts/:slug',
		component: PostSingleComponent
	},
	{
		path: 'app/admin',
		component: AdminComponent,
		// canActivate: [AuthGuard],
		children: [
			// { path: '', redirectTo: 'form', pathMatch: 'full' },
			{ path: 'submit-result', component: SubmitResultComponent },
			// {
			// 	path: 'partners',
			// 	component: PartnersComponent,
			// 	children: [
			// 		{ path: 'place-partners', component: PlacePartnerListComponent },
			// 		{ path: 'deco-partners', component: DecoPartnerListComponent },
			// 		{ path: 'dress-partners', component: DressPartnerListComponent },
			// 		{ path: 'snap-partners', component: SnapPartnerListComponent },
			// 		{ path: 'dinner-partners', component: DinnerPartnerListComponent },
			// 		{ path: 'etc-partners', component: EtcPartnerListComponent },
			// 	]
			// },
			// {
			// 	path: 'consults',
			// 	component: ConsultsComponent,
			// 	children: [
			// 		{ path: 'one-to-one', component: OneToOneConsultListComponent },
			// 		{ path: 'visit', component: VisitConsultListComponent },
			// 		{ path: 'board', component: BoardConsultListComponent },
			// 	]
			// },
			// { path: 'calendar', component: CalendarComponent },
		]
	},
];
@NgModule({
	imports: [RouterModule.forRoot(routes)],
	exports: [RouterModule],
	providers: [AuthGuard]
})
export class AppRoutingModule {}
