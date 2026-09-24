from django.contrib import admin
from django.contrib.auth.models import User

from .models import Profile


# =========================================================
# UTILISATEUR
# =========================================================

admin.site.unregister(User)


@admin.register(User)
class UserAdmin(admin.ModelAdmin):

    list_display = (
        "username",
        "email",
        "first_name",
        "last_name",
        "is_staff",
        "is_active",
        "date_joined",
    )

    list_filter = (
        "is_staff",
        "is_active",
        "date_joined",
    )

    search_fields = (
        "username",
        "email",
        "first_name",
        "last_name",
    )

    ordering = (
        "-date_joined",
    )


# =========================================================
# PROFIL
# =========================================================

@admin.register(Profile)
class ProfileAdmin(admin.ModelAdmin):

    list_display = (
        "user",
        "last_connection",
    )

    search_fields = (
        "user__username",
        "user__email",
    )

    readonly_fields = (
        "last_connection",
    )

