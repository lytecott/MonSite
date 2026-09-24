
from django.urls import path
from . import views

urlpatterns = [

    # ==========================================
    # ACCUEIL
    # ==========================================

    path(
        "",
        views.base,
        name="home"
    ),

    # ==========================================
    # TABLEAU DE BORD UTILISATEUR
    # ==========================================

    path(
        "dashboard/",
        views.home2,
        name="home2"
    ),

    # ==========================================
    # AUTHENTIFICATION
    # ==========================================

    path(
        "login/",
        views.login_view,
        name="login"
    ),

    path(
        "signup/",
        views.signup,
        name="signup"
    ),

    path(
        "logout/",
        views.logout_view,
        name="logout"
    ),

    # ==========================================
    # PROFIL UTILISATEUR
    # ==========================================

    path(
        "profile/",
        views.profile,
        name="profile"
    ),

    path(
        "change-password/",
        views.change_password,
        name="change_password"
    ),

    # ==========================================
    # ADMINISTRATION
    # ==========================================

    path(
        "admin-home/",
        views.admin_home,
        name="admin_home"
    ),

    path(
        "admin/users/",
        views.user_list,
        name="user_list"
    ),

    path(
        "admin/users/<int:user_id>/edit/",
        views.edit_user,
        name="edit_user"
    ),

    path(
        "admin/users/<int:user_id>/disable/",
        views.disable_user,
        name="disable_user"
    ),

    path(
        "admin/users/<int:user_id>/reset-password/",
        views.reset_password,
        name="reset_password"
    ),

    path(
        "admin/users/<int:user_id>/delete/",
        views.delete_user,
        name="delete_user"
    ),
]
