
from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required, user_passes_test
from django.contrib.auth.models import User
from django.contrib.auth.forms import (
    AuthenticationForm,
    PasswordChangeForm
)
from django.contrib.auth import (
    login,
    logout,
    update_session_auth_hash
)
from django.contrib import messages
from django.http import HttpResponseNotAllowed

from .forms import (
    SignUpForm,
    UserUpdateForm,
    ProfileUpdateForm
)

from .models import Profile


# =========================================================
# VERIFICATION ADMIN
# =========================================================

def is_admin(user):
    """
    Vérifie si l'utilisateur connecté est administrateur.
    """

    return user.is_authenticated and user.is_staff


# =========================================================
# ACCUEIL PUBLIC
# =========================================================

def base(request):
    """
    Page d'accueil publique.
    """

    return render(
        request,
        "home.html"
    )


# =========================================================
# ESPACE UTILISATEUR
# =========================================================

@login_required
def home2(request):
    """
    Page d'accueil de l'utilisateur connecté.
    """

    return render(
        request,
        "home2.html"
    )


# =========================================================
# ESPACE ADMINISTRATEUR
# =========================================================

@login_required
@user_passes_test(is_admin)
def admin_home(request):
    """
    Tableau de bord administrateur.
    """

    return render(
        request,
        "admin_home.html"
    )


# =========================================================
# CONNEXION
# =========================================================

def login_view(request):
    """
    Connexion des utilisateurs et administrateurs.
    """

    # -----------------------------------------------------
    # Si l'utilisateur est déjà connecté
    # -----------------------------------------------------

    if request.user.is_authenticated:

        if request.user.is_staff:
            return redirect("admin_home")

        return redirect("profile")

    # -----------------------------------------------------
    # Traitement du formulaire
    # -----------------------------------------------------

    if request.method == "POST":

        form = AuthenticationForm(
            request,
            data=request.POST
        )

        if form.is_valid():

            user = form.get_user()

            login(request, user)

            messages.success(
                request,
                f"Bienvenue {user.username} !"
            )

            # Administrateur
            if user.is_staff:
                return redirect("admin_home")

            # Utilisateur normal
            return redirect("profile")

    else:

        form = AuthenticationForm()

    return render(
        request,
        "accounts/login.html",
        {
            "form": form
        }
    )


# =========================================================
# INSCRIPTION
# =========================================================

def signup(request):
    """
    Création d'un nouveau compte utilisateur.
    """

    # -----------------------------------------------------
    # Empêcher un utilisateur connecté de s'inscrire
    # -----------------------------------------------------

    if request.user.is_authenticated:
        return redirect("home2")

    # -----------------------------------------------------
    # Traitement du formulaire
    # -----------------------------------------------------

    if request.method == "POST":

        form = SignUpForm(
            request.POST
        )

        if form.is_valid():

            user = form.save()

            # Le signal crée automatiquement le Profile
            login(request, user)

            messages.success(
                request,
                "Compte créé avec succès !"
            )

            return redirect("home2")

    else:

        form = SignUpForm()

    return render(
        request,
        "registration/signup.html",
        {
            "form": form
        }
    )


# =========================================================
# DECONNEXION
# =========================================================

@login_required
def logout_view(request):
    """
    Déconnexion uniquement avec une requête POST.
    """

    if request.method != "POST":

        return HttpResponseNotAllowed(
            ["POST"]
        )

    logout(request)

    messages.success(
        request,
        "Vous êtes maintenant déconnecté."
    )

    return redirect("home")


# =========================================================
# PROFIL UTILISATEUR
# =========================================================

@login_required
def profile(request):
    """
    Affichage et modification du profil
    de l'utilisateur connecté.
    """

    # -----------------------------------------------------
    # Récupérer ou créer automatiquement le profil
    # -----------------------------------------------------

    profile_object, created = Profile.objects.get_or_create(
        user=request.user
    )

    # -----------------------------------------------------
    # Modification du profil
    # -----------------------------------------------------

    if request.method == "POST":

        user_form = UserUpdateForm(
            request.POST,
            instance=request.user
        )

        profile_form = ProfileUpdateForm(
            request.POST,
            request.FILES,
            instance=profile_object
        )

        if (
            user_form.is_valid()
            and profile_form.is_valid()
        ):

            user_form.save()
            profile_form.save()

            messages.success(
                request,
                "Votre profil a été mis à jour avec succès !"
            )

            return redirect("profile")

    else:

        user_form = UserUpdateForm(
            instance=request.user
        )

        profile_form = ProfileUpdateForm(
            instance=profile_object
        )

    return render(
        request,
        "accounts/profile.html",
        {
            "user_form": user_form,
            "profile_form": profile_form,
            "profile": profile_object,
        }
    )


# =========================================================
# CHANGEMENT DE MOT DE PASSE
# =========================================================

@login_required
def change_password(request):
    """
    Permet à l'utilisateur de changer son propre
    mot de passe.
    """

    if request.method == "POST":

        form = PasswordChangeForm(
            request.user,
            request.POST
        )

        if form.is_valid():

            form.save()

            # Conserver la session après changement
            update_session_auth_hash(
                request,
                request.user
            )

            messages.success(
                request,
                "Votre mot de passe a été changé avec succès !"
            )

            return redirect("profile")

    else:

        form = PasswordChangeForm(
            request.user
        )

    return render(
        request,
        "users/change_password.html",
        {
            "form": form
        }
    )


# =========================================================
# LISTE DES UTILISATEURS
# =========================================================

@login_required
@user_passes_test(is_admin)
def user_list(request):
    """
    Liste de tous les utilisateurs.
    """

    users = User.objects.all().order_by(
        "-date_joined"
    )

    return render(
        request,
        "admin/user_list.html",
        {
            "users": users
        }
    )


# =========================================================
# MODIFIER UN UTILISATEUR
# =========================================================

@login_required
@user_passes_test(is_admin)
def edit_user(request, user_id):
    """
    Modification d'un utilisateur par l'administrateur.
    """

    user = get_object_or_404(
        User,
        id=user_id
    )

    if request.method == "POST":

        form = UserUpdateForm(
            request.POST,
            instance=user
        )

        if form.is_valid():

            form.save()

            messages.success(
                request,
                f"Utilisateur {user.username} mis à jour !"
            )

            return redirect("user_list")

    else:

        form = UserUpdateForm(
            instance=user
        )

    return render(
        request,
        "admin/edit_user.html",
        {
            "form": form,
            "user": user
        }
    )


# =========================================================
# ACTIVER / DESACTIVER UN UTILISATEUR
# =========================================================

@login_required
@user_passes_test(is_admin)
def disable_user(request, user_id):
    """
    Active ou désactive un utilisateur.
    """

    if request.method != "POST":

        return HttpResponseNotAllowed(
            ["POST"]
        )

    user = get_object_or_404(
        User,
        id=user_id
    )

    # -----------------------------------------------------
    # Empêcher l'admin de se désactiver lui-même
    # -----------------------------------------------------

    if user == request.user:

        messages.error(
            request,
            "Vous ne pouvez pas désactiver votre propre compte."
        )

        return redirect("user_list")

    # -----------------------------------------------------
    # Changement d'état
    # -----------------------------------------------------

    user.is_active = not user.is_active

    user.save(
        update_fields=["is_active"]
    )

    if user.is_active:
        status = "activé"
    else:
        status = "désactivé"

    messages.success(
        request,
        f"Utilisateur {user.username} {status} !"
    )

    return redirect("user_list")


# =========================================================
# REINITIALISER LE MOT DE PASSE
# =========================================================

@login_required
@user_passes_test(is_admin)
def reset_password(request, user_id):
    """
    Permet à l'administrateur de définir un nouveau
    mot de passe pour un utilisateur.
    """

    user = get_object_or_404(
        User,
        id=user_id
    )

    if request.method == "POST":

        form = PasswordChangeForm(
            user,
            request.POST
        )

        if form.is_valid():

            form.save()

            messages.success(
                request,
                f"Mot de passe de {user.username} réinitialisé !"
            )

            return redirect("user_list")

    else:

        form = PasswordChangeForm(
            user
        )

    return render(
        request,
        "admin/reset_password.html",
        {
            "form": form,
            "user": user
        }
    )


# =========================================================
# SUPPRIMER UN UTILISATEUR
# =========================================================

@login_required
@user_passes_test(is_admin)
def delete_user(request, user_id):
    """
    Suppression d'un utilisateur par l'administrateur.
    """

    # -----------------------------------------------------
    # Suppression uniquement avec POST
    # -----------------------------------------------------

    if request.method != "POST":

        return HttpResponseNotAllowed(
            ["POST"]
        )

    user = get_object_or_404(
        User,
        id=user_id
    )

    # -----------------------------------------------------
    # Empêcher l'admin de supprimer son propre compte
    # -----------------------------------------------------

    if user == request.user:

        messages.error(
            request,
            "Vous ne pouvez pas supprimer votre propre compte."
        )

        return redirect("user_list")

    # -----------------------------------------------------
    # Suppression
    # -----------------------------------------------------

    username = user.username

    user.delete()

    messages.success(
        request,
        f"L'utilisateur {username} a été supprimé."
    )

    return redirect("user_list")
