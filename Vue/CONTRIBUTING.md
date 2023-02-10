# Оглавление

- [Scripts](CONTRIBUTING.md#scripts) Правила для скриптов
- [Templates](CONTRIBUTING.md#templates) Правила для разметки
- [Styles](CONTRIBUTING.md#styles) Правила для стилей
- [Comments](CONTRIBUTING.md#comments) Правила для комментариев
- [Package.json](CONTRIBUTING.md#packagejson) Обязательные поля для заполнения
- [Project Tree](CONTRIBUTING.md#project-tree) Роадмэп проекта
- [Code Style](CONTRIBUTING.md#code-style) Правила по именованию и прочее
- [Git](CONTRIBUTING.md#git) Правила по именованию и прочее
- [Helpfull Links](CONTRIBUTING.md#helpfull-links) Полезные ссылки
<!-- TODO: добавить в .editorconfig рекомендуемые расширения (VS Code, Shtorm) -->
<!-- TODO: перед npm install проверять версию npm init vue@latest (официальной версии пресета) -->

## Readme

- [NPM commands](README.md#npm-commands) Список команд из консоли для проекта
- [Git Flow](README.md#git-flow) Правила ветвления на проекте
- [Description](README.md#description) Описание ключевых моментов в настройке проекта, не связанные с разработкой

## Scripts

- Методы-обработчики именуются: `{{цель}}{{действие}}Handler`. Пример: `userChangeHandler`.
- Булевские переменные именуются: `is{{цель}}{{условие}}`. Пример: `isModalVisible`, `hasModalTitle`.
- Методы-действия именуются: `{{глагол}}{{контекст}}`. Пример: `getUser`, `generateStyle` и т.д. Так же глагол должен использоваться один и тот же во всех методах-действиях одного типа. Например, получение чего-либо - всегда `get...`, не использовать синонимы, по типу `take` и прочие. Если `generate` - всегда `generate`, не нужно мешать с, например, `make`.
- Стараемся типизировать всё (`any` не приветствуется (P.S. если тип неизвестен, лучше использовать `unknown`, чем `any`)). Если есть место, где по каким-то причинам получается использовать только `any`, то лучше этот момент описать или вынести на обсуждение (при необходимости).
- Используем синтаксический сахар `<script setup>`, рекомендуемый при Composition API внутри SFC

## Templates

<!-- TODO: Префикс имени проекта/сущности проекта (компонент, страница)? -->
- Именование компонентов используется в стиле `kebab-case` и использоватся 2 или более слова (включая префикс) во избежание коллизий с нативным HTML. Пример:

```html
<stk-some-long-name v-model="model" ... />
<stk-component v-model="model" ... />
<c-component v-model="model" ... />
```

## Styles

- Именование классов по методологии BEM `block-name__item-name--modificator`
- Именование айдишников `kebab-case`
- Название переменной формируется: `$scope-name--param-name`
- Локальная стилизация компонента находится в нём же (паттерн SFC) и не затрагивает внешние модули (scoped)

```html
<template>
  <div class="с-card">
    <div class="с-card__header" id="c-card-header">
      <h2>Header</h2>
    </div>
    <div class="с-card__info">
      <span>Info</span>
    </div>
    <div class="с-card__price с-card__price--hidden">
      <span>1000</span>
    </div>
  </div>
</template>

<style scoped lang="scss">
  .с-card {
    $root: &;
    $height--primary: 100px;

    --с-card--height: $height--primary * 0.5;

    :deep(.c-class) {
      #{$root}__info {
        height: var(--с-card--height);
      }
    }
  }
</style>
```

- Глобальные классы
  - Отступы: `{{вид_отступа}}-{{размер_экрана_если_требуется}}-{{размер_отступа}}`. Пример: `m-4`, `ml-4`, `ml-lg-4`, `p-4`, `pl-4`, `pl-lg-4`
  - Цвета: `{{вид_заливки}}-{{наименование_цвета}}`. Пример: `color-primary`, `bg-color-secondary`
  - Не используем в проекте `font-weight`, `font-size` и `font-family`, вместо этого используем абстрактные классы `.h1`, `.text-sm`, `.bold` и т. д.
- В svg-файлах в папке `src/assets/icons` в заливку (fill) ставить `currentColor`
- Избегайте использование `!important`, линтеры будут выкидывать warning. Если не удаётся отказаться от него: добавляем исключение и описываем почему используем:

```scss
.input {
  // описываем почему используем !important
  content: none !important; /* stylelint-disable-line declaration-no-important */
}
```

## Comments

- Если в задаче требуется **времено** скрыть кусок приложения, то эта часть оборачивается соответствующими правила для каждого из блоков файла и выше ставится номер задачи и её заголовок. Например:

```html
<!-- TASK-ID | Task title -->
<!-- <div /> -->
```

```ts
// TASK-ID | Task title

/**
 * TASK-ID 
 * Task title
 */
```

```scss
// TASK-ID | Task title
// display: flex;
```

- Если нужно времено отложить разработку в текущем месте приложения и чтобы не забыть доработать, условимся помечать такие места двумя способами `FIXME: description` или `TODO: description`.
- `FIXME:` - маркируется то место в коде, которое необходимо закончить в рамках текущей задачи, и если в пришедшем МР есть хотя бы один такой комментарий, моментально возвращать задачу на доработку.
- `TODO:` - маркируется то место в коде, которое планируется выполнить в другой задаче.
- `description`: краткое описание, но передающее основной смысл. Писать так, чтобы другой человек понял ваш комментарий. Не приветствуются комментарии следующего содержания `не забыть поправить` или `Петя сказал, что поправит это своим МР` (не указана цель правки).. Например:

```html
<!-- TODO: Здесь запланировано место для шапки -->
```

```ts
// FIXME: При загрузке приложение падает, спросить SOMEBODY чтобы помог

/**
 * TODO: 
 * Сделать рефакторинг метода получения пользователей
 */
```

```scss
// FIXME: разобраться почему стили не применяются в сафари
// display: flex;
```

## Package.json

## Project Tree

<!-- TODO: Префикс имени проекта/сущности проекта (компонент, страница)? -->
- Для именования SingleFileComponent использовать 2 или более слова (включая префикс). Имя компонента должно состоять из полных слов (без сокращений). Пример: `CCard.vue`, `CFormItem.vue`, `PAuthorization.vue`
- Директории именуются в стиле `camelCase`. Пример: `user`, `userDetail`
- Для именования файлов и папок, группирующие файлы одной сущности, использовать PascalCase. Пример:
  - componentsExampleFolder
    - global
      - MyComponent
        - MyComponent.vue
      - MyGroupComponent
        - MyGroupComponent.locale.ts - файл переводов
        - MyGroupComponent.d.ts - описание типов
        - MyGroupComponent.vue

## Code Style

- В script определённый порядок (сверху вниз) описания переменных, методов и т. д., а именно:
  - model
  - props/emits и их типизация
  - composables
  - ref/reactive
  - computed
  - watch
  - lifecycle hooks:
    - onServerPrefetch
    - onActivated
    - onBeforeMount
    - onMounted
    - onBeforeUpdate
    - onUpdated
    - onDeactivated
    - onBeforeUnmount
    - onUnmounted
    - onErrorCaptured
  - methods
- Template компонента должен быть максимально чистым (за исключением самостоятельных компонентов)
- В template осуждается использование "голых" выражений, например: `<c-card @click="isModalVisible = !isModalVisible" />`, `<div>{{ new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB' }).format(amount) }}</div>`. Вместо этого вынести подобную логику, либо в метод-обработчик/действие, либо, если позволяет логика, в `computed`, для чистоты кода
- В style (только scoped) именование селектора должно соответствовать названию файла. Например: `CCard.vue` => `.c-card { ... }`

```html
<!-- CCard.vue -->
<template>
  <div class="c-card">
    <div class="c-card__info">
      <span> {{ userWithId }} </span>
      <span v-if="!isMobile"> Баланс: {{ balance }} </span>
    </div>
    <button class="c-card__action c-card__action--secondary" @click="userChangeHandler"> Click to change user </button>
  </div>
</template>

<script setup lang="ts">
  import { ref, onMounted } from 'vue'

  type Props = {
    // some params...
  }

  type Emits = {
    // some params...
  }

  const props = defineProps<Props>()
  // Опционально, если надо дефолтные значения указать
  // const props = withDefaults(defineProps<Props>(), {
  //   // some params...
  // })
  const emit = defineEmits<Emits>()

  const { isMobile } = useScreen()

  let id = ref(1)

  const userWithId = computed(() => `Пользователь ${id}`)
  const balance = computed(() => new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB' }).format(props.balance))

  onMounted(() => {
    //some logic...
  })

  const userChangeHandler = () => {
    id.value += 1
  }
</script>

<style scoped lang="scss">
  --c-card--text: #c0c0c0;
  --c-card--btn-background: #a0a0c0;

  .c-card {
    $root: &;

    display: flex;
    align-items: center;
    justify-content: center;

    &__info {
      color: var(--c-card--text);
    }

    &__action {
      ...
      &--secondary {
        background: var(--c-card--btn-background);
      }
      #{$root}__some-another-class {
        ...
      }
    }
  }
</style>
```

## Git

1. Репозиторий должен быть настроен так, чтобы МР было невозможно подтвердить, пока не решены все замечания по МР или пайплайн завершился с ошибками
    - `settings` -> `general` -> `Merge requests` -> `Pipelines must succeed` (при наличи настроек gitlab-ci на проекте)
    - `settings` -> `general` -> `Merge requests` -> `All discussions must be resolved`
1. Гит репозиторий должен иметь две обязаельные ветки `main`/`master` и `dev`/`develop`
1. Ветке `main`/`master` - присваивается модификатор `protected` доставка кода в которую осуществляется **только** через мерж реквест
1. Ветке `dev`/`develop` - присваивается модификатор `protected` доставка кода в которую осуществляется как через мерж реквест, так и напрямую `project member` с правами `maintainer` и выше (для того чтобы при прямом МР в `main`/`master`, когда нужно выкатить хотфикс, в обход готовящемуся релизу, после, подтянуть изменения из `main`/`master` и запушить их в `dev`/`develop`)
1. **Любая** задача решается в своей отдельной ветке

### Ветки и коммиты

- Название ветки формируется из `TASK-ID`, где `TASK-ID` - Id задачи в Jira, без каких либо вложенностей (такие как: `feat/CP-1111` не приветствуются, просто `CP-1111`)
- В случае когда нужно нескольким разработчикам работать над одной большой задачей одновременно:
  1. Попросить ТимЛида или Менеджера разбить на более мелкие задачи с указанием иерархии и зависимости (какая какую блокирует через инструмент линкования в джире)
  1. Создать ветку с основным `TASK-ID` и запушить
      - Ответвится от ветки `TASK-ID` добавив модификатор с никнеймом разработчика, например: `CP-2023-saitama`
      - По завершению выполнения сделать МР в `TASK-ID`
      - По завершению, с окончательным результатом, сделаь МР в `dev`/`develop`

### Merge requests

Merge request формируется, следующим образом `Draft: TYPE: TASK-ID | TASK-TITLE`, где:

- `Draft` - опциональный модификатор, указывающий что МР не готов к проверке ревьюером.
- `TYPE` - модификатор указывающий вид проводимых работ:
  - **feat**：Новые фичи
  - **fix**：bugfix
  - **docs**：Обновление документации
  - **style**：Модификации кода, не влияющие на логику программы (изменение пробельных символов, форматирование отступов, заполнение отсутствующих точек с запятой и т. д. без изменения логики кода)
  - **refactor**：Рефакторинг кода (ни новых функций, ни исправлений ошибок)
  - **perf**：Производительность, оптимизация
  - **test**：Добавление новых тестов или обновление существующих
  - **build**：Основная цель - изменить фиксацию системы сборки проекта (например, глюк, новый веб-пакет, конфигурация накопительного пакета и т.д.).
  - **ci**：Основная цель - изменить коммиты, в которых проект продолжает процесс интеграции (например, Travis, Jenkins, GitLab CI, Circle и т. д.).
  - **chore**：Другие типы, не принадлежащие к указанным выше типам
  - **revert**：Откатить предыдущий коммит
- `TASK-ID` - Id задачи в Jira
- `TASK-TITLE` - полное название задачи в Jira (заголовок)

Требования к MR:

- Заголовок должен кратко изложить суть данного изменения/нововведения.
- MR должен иметь код только по задаче указанной в теме.

## Helpfull Links

- [Style Guide](https://vuejs.org/style-guide/) (обязательно к прочтению)
- [Style Guide (Ru)](https://v3.ru.vuejs.org/ru/style-guide/) (обязательно к прочтению)
- [Composition API](https://vuejs.org/api/options-composition.html)
- [Best Practices from Vue](https://vuejs.org/guide/best-practices/production-deployment.html)
- [Tips & Best Practices](https://medium.com/js-dojo/vue-3-tips-best-practices-54aec91d95dc)
- [6 Tips for Building Large Scale Applications](https://vueschool.io/articles/vuejs-tutorials/6-tips-for-building-large-scale-vue-js-3-applications/)
- [Script setup](https://vuejs.org/api/sfc-script-setup.html#script-setup)
- [TypeScript](https://www.typescriptlang.org/docs/)
- [TypeScript. Utility Types](https://www.typescriptlang.org/docs/handbook/utility-types.html)
- [TypeScript. Creating Types from Types](https://www.typescriptlang.org/docs/handbook/2/types-from-types.html)
- [SCSS. Documentation (Ru)](https://sass-scss.ru/documentation/)
- [SCSS. Documentation](https://sass-lang.com/documentation)
- [SCSS. Playground](https://www.sassmeister.com/)
