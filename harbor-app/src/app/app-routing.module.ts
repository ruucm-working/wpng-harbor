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
	{ path: '', redirectTo: 'app', pathMatch: 'full' },
	{ path: 'app', component: HomePageComponent },
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
	}
];
@NgModule({
	imports: [RouterModule.forRoot(routes)],
	exports: [RouterModule],
	providers: [AuthGuard]
})
export class AppRoutingModule {}
