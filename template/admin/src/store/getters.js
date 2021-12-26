const getters = {
  userId: state => state.user.user_id,
  device: state => state.app.device,
  token: state => state.user.token,
  avatar: state => state.user.avatar,
  name: state => state.user.nickname,
  user: state => state.user.user,
  roles: state => state.user.roles,
  menus: state => state.permission.menus,
  permission: state => state.permission.permission
}
export default getters
