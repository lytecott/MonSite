from django.db import models
from django.contrib.auth.models import User


class Profile(models.Model):

    user = models.OneToOneField(
        User,
        on_delete=models.CASCADE, related_name='profile'
    )

    profile_picture = models.ImageField(
        upload_to="profile_photos/",
        blank=True,
        null=True
    )


    last_connection = models.DateTimeField(
        blank=True,
        null=True
    )

    bio = models.TextField(blank=True)

    def __str__(self):
        return f"Profil de {self.user.username}"

