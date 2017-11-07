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
const routes: Routes = [
	{ path: '', component: HomePageComponent },
	{ path: 'login', component: UserLoginComponent, },
	{ path: 'items', component: ItemsListComponent, canActivate: [AuthGuard] },
	{ path: 'notes', component: NotesListComponent, canActivate: [AuthGuard] },
	// uploads are lazy loaded
	{ path: 'uploads', loadChildren: './uploads/shared/upload.module#UploadModule', canActivate: [AuthGuard] },
	{
		path: 'posts',
		component: PostListComponent,
		pathMatch: 'full'
	},
	{
		path: 'posts/:slug',
		component: PostSingleComponent
	}
];
@NgModule({
	imports: [RouterModule.forRoot(routes)],
	exports: [RouterModule],
	providers: [AuthGuard]
})
export class AppRoutingModule {}
