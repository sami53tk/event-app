# **Laravel M1 YNOV**

## **Implémentation d’un système d’authentification**  
Il s’agit d’une plateforme de gestion d’événements disposant de plusieurs rôles :  
- **Admin**  
- **Organisateur**  
- **Client**  

### **Permissions**  
- **L’admin** peut gérer les membres de la plateforme : créer un compte, le supprimer, etc.  
- **L’admin et l’organisateur** peuvent créer et gérer des événements (**l’organisateur ne peut gérer que ses propres événements**).  
- **Le client** peut s’inscrire sur la plateforme et aux événements. Il doit ensuite retrouver ses événements sur son interface.  

📩 **La plateforme envoie des emails lorsque cela est nécessaire** (vous définirez les cas pertinents).  

---

## **Définition d’un événement**  
Un événement est défini par :  
- Un titre  
- Une image bannière  
- Une description  
- Une date  
- Un lieu  
- Un statut  
- Un nombre maximum de participants (**doit empêcher de nouvelles inscriptions une fois la limite atteinte**)  
- Etc. (ajoutez d’autres attributs si nécessaire)  

---

## **Bonus** ✨  
✅ **Envoyer un rappel 24h avant le début de l’événement** à tous les participants.  
✅ **Permettre l’annulation d’un événement** et informer les utilisateurs concernés. L’événement ne disparaît pas de la plateforme mais change d’état.  

---

## **Bonus du bonus** 🚀  
✅ **Intégrer Stripe** pour la réservation des événements (**pensez à mettre à jour votre modèle `Event` et la base de données**).  

---

## **Consignes importantes**  
- **Respecter la segmentation des migrations**.  
- **Faire des commits réguliers** sur un repository **public ou privé** et m’inviter dessus.  

📩 **Email Git** : swann.leroy@leroy-design.com  
👤 **Leroy-Design** : Leroy-Design  

---

✅ **Bon courage !** 🚀
