from django import forms
from django.contrib.auth.models import User

from .models import Profile


class SignUpForm(forms.ModelForm):

    password = forms.CharField(
        label="Mot de passe",
        widget=forms.PasswordInput
    )

    password_confirm = forms.CharField(
        label="Confirmer le mot de passe",
        widget=forms.PasswordInput
    )

    class Meta:

        model = User

        fields = (
            "username",
            "email",
            "first_name",
            "last_name",
        )

    def clean_email(self):

        email = self.cleaned_data["email"]

        if User.objects.filter(
            email__iexact=email
        ).exists():

            raise forms.ValidationError(
                "Cette adresse e-mail est déjà utilisée."
            )

        return email

    def clean(self):

        cleaned_data = super().clean()

        password = cleaned_data.get("password")
        password_confirm = cleaned_data.get(
            "password_confirm"
        )

        if (
            password
            and password_confirm
            and password != password_confirm
        ):

            raise forms.ValidationError(
                "Les mots de passe ne correspondent pas."
            )

        return cleaned_data

    def save(self, commit=True):

        user = super().save(commit=False)

        user.set_password(
            self.cleaned_data["password"]
        )

        if commit:
            user.save()

        return user


class UserUpdateForm(forms.ModelForm):

    class Meta:

        model = User

        fields = (
            "username",
            "email",
            "first_name",
            "last_name",
        )


class ProfileUpdateForm(forms.ModelForm):

    class Meta:

        model = Profile

        fields = (
            "profile_picture",
        )

